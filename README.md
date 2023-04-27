# BSUIR-QUEUE-BOT

The main goal of this telegram bot is to take the place in queue for lessons.
I used https://iis.bsuir.by/api to get schedule of university and then based on group of student,
that he provide give them functionality to {take a place|check his place|leave his place|check queue at certain day}.
For better interactions with database i used Doctrine orm.

## Commands (App\Commands)

```
<?php

interface Command
{
    public function execute() : void;
}
```

When user inputs any message to bot it checks if this valid text or not

For example: <br>
✅/command <br>
❌any text

If valid then checks if this command exist
```
<?php

public function createNewCommand(string $message): ?Command
    {
        $command = explode('@', $message)[0];
        $classname = str_replace('/', '', $command, $replaces) . 'Command';
        
        //there are two level of access Teacher and Student,
        //later bot check if user has access to Teacher commands
        switch (true) {
            case class_exists(__NAMESPACE__ . '\\' . 'StudentsCommands' . '\\' . $classname):
                $classname = __NAMESPACE__ . '\\' . 'StudentsCommands' . '\\' . $classname;
                break;
            case class_exists(__NAMESPACE__ . '\\' . 'TeacherCommands' . '\\' . $classname):
                $classname = __NAMESPACE__ . '\\' . 'TeacherCommands' . '\\' . $classname;
                break;
            default:
                break;
        }

        if (class_exists($classname) && $replaces == 1 && $command[0] == '/') {
            return new $classname($this->telegram, $this->params, $this->stateManager);
        }
        return null;
    }
```

And after than executes it.

## States (App\States)

Also there is one feature in this bot called states, <br>
When user executes some command, they may have functionality to set State to user:<br>
```
<?php

$this->stateManager->setState(
            $user->getTgId(),
            $this->getButtonsId($response),
            new ChoosingDateState($this->telegram, $this->stateManager)
        );
```

And when user has some state input in future will be processed according to this state.

```
<?php

interface State
{
    public function handleInput(array $params): void;
}
```

Each State handle input in different ways.

## Other

There are 3 classes to work with several apis:
1. App\Telegram - provide intereaction with Telegram bot api
2. App\Schedule - help get information about lessons of university
3. App\Github - get this repository stats, to see if user starred this repository, then give him additional functionality

