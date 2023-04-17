<?php

namespace App\States;


use App\App;
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

        if ($params['callback_data'] === null) {
            $this->telegram->deleteMessage($params['chat_id'], $messageId);
            $message = $this->telegram->sendButtons($params['chat_id'], $buttons);
            $newMessageId = $this->getMessageId($message);
            $this->stateManager->changeMessageId($params['user_id'], $newMessageId);
        } else {
            $lessonDate = new DateTime(
                Schedule::getLessons($user->getGroup())[$params['callback_data']]['date']
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
        $this->telegram->sendMessage($chatId, 'Choose place');
    }

    private function getMessageId(string $message): ?int
    {
        return json_decode($message, true)['result']['message_id'] ?? null;
    }


}

