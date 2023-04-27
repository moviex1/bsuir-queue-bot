<?php

namespace App\States;


use App\App;
use App\Github;
use App\Message;
use App\Schedule;
use Database\Entity\Queue;
use Database\Entity\User;
use DateTime;
use Messages\Emojis;

class ChoosingDateState extends State
{
    public function handleInput(array $params): void
    {
        $user = $this->stateManager->getStateData($params['user_id'], 'user');
        $buttons = $this->stateManager->getStateData($user->getTgId(), 'buttons');
        $command = $this->stateManager->getStateData($user->getTgId(), 'command');
        $messageId = $this->stateManager->getMessageId($user->getTgId());
        $callbackData = $params['callback_data'];

        if ($callbackData === null) {
            $this->resendButtons($params['chat_id'], $messageId, $buttons, $params['user_id']);
            return;
        }

        if (!$this->checkDate($user, $callbackData)) {
            $this->telegram->sendMessage($params['chat_id'], Message::make('star'));
            $this->resendButtons($params['chat_id'], $messageId, $buttons, $params['user_id']);
            return;
        }

        $lessonDate = new DateTime(
            Schedule::getLessons($user->getGroup())[$callbackData]['date']
        );

        $this->telegram->deleteMessage($params['chat_id'], $messageId);
        switch ($command) {
            case 'queue':
                $this->handleQueueCommand($user, $lessonDate, $params['chat_id']);
                break;
            case 'list':
                $this->handleListCommand($user, $lessonDate, $params['chat_id']);
                break;
            default:
                break;
        }
    }

    private function resendButtons(int $chatId, int $messageId, array $buttons, int $userId): void
    {
        $this->telegram->deleteMessage($chatId, $messageId);
        $message = $this->telegram->sendButtons($chatId, Message::make('buttons.dateButtons'), $buttons);
        $newMessageId = $this->getMessageId($message);
        $this->stateManager->changeMessageId($userId, $newMessageId);
    }

    private function checkDate(User $user, string $callbackData): bool
    {
        $github = new Github('moviex1', 'bsuir-queue-bot');
        $starredUsers = $github->getStaredUsers();
        if ($callbackData == '1') {
            if (!$user->getGit()) {
                return false;
            }
            return in_array($user->getGit(), $starredUsers);
        }

        return true;
    }


    private function handleListCommand(User $user, DateTime $lessonDate, int $chatId)
    {
        $queueRepository = App::entityManager()->getRepository(Queue::class);
        $reserves = $queueRepository->getAllByDate($lessonDate);
        $this->stateManager->removeUserState($user->getTgId());
        $this->telegram->sendMessage(
            $chatId,
            Message::make('list', [
                'reserves' => $reserves,
                'emojis' => Emojis::getEmojis(),
                'lessonDate' => $lessonDate->format('Y-m-d h:i:s')
            ])
        );
    }

    private function handleQueueCommand(User $user, DateTime $lessonDate, int $chatId)
    {
        $this->stateManager->changeState(
            $user->getTgId(),
            new EnteringPlaceState($this->telegram, $this->stateManager)
        );
        $this->stateManager->addDataToState($user->getTgId(), [
            'lessonDate' => $lessonDate,
            'user' => $user
        ]);
        $this->telegram->sendMessage($chatId, Message::make('queue.choosePlace'));
    }

    private function getMessageId(string $message): ?int
    {
        return json_decode($message, true)['result']['message_id'] ?? null;
    }


}

