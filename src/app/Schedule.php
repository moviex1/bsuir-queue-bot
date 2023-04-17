<?php

namespace App;

use Curl\Curl;
use DateTime;

date_default_timezone_set('Europe/Moscow');


class Schedule
{


    private static function getGroupSchedule(string $group): ?array
    {
        $urlGroupQuery = 'https://iis.bsuir.by/api/v1/schedule?studentGroup=';
        $curl = (new Curl())->get($urlGroupQuery . $group);
        if ($curl->isSuccess()) {
            $data = json_decode($curl->response, true)['schedules'];
        } else {
            $data = null;
        }
        $curl->close();
        return $data;
    }

    private static function getCurrentWeek(): ?string
    {
        $urlCurrentWeek = 'https://iis.bsuir.by/api/v1/schedule/current-week';
        $curl = (new Curl())->get($urlCurrentWeek);
        if ($curl->isSuccess()) {
            $currentWeek = $curl->response;
        } else {
            $currentWeek = null;
        }
        $curl->close();
        return $currentWeek;
    }

    private static function getLessonDate(string $day, string $time, int $week): string
    {
        return date(
            'Y-m-d H:i:s',
            strtotime(self::getDay($day) - date('w') . ' day ' . $time . " $week" . ' week')
        );
    }

    private static function getSubGroup(int $subgroup): string
    {
        return ['(Общ.)', '(1 подгр.)', '(2 подгр.)'][$subgroup];
    }

    private static function getDay(string $day) : int
    {
        return [
            'Понедельник' => 1,
            'Вторник' => 2,
            'Среда' => 3,
            'Четверг' => 4,
            'Пятница' => 5
        ][$day];
    }

    private static function parseSchedule(array $schedules, int $currentWeek): array
    {
        $lessons = [];
        foreach ($schedules as $day => $schedule) {
            foreach ($schedule as $subjects) {
                if ($subjects['subject'] == 'ОАиП' && $subjects['lessonTypeAbbrev'] == 'ЛР') {
                    foreach ($subjects['weekNumber'] as $weekNumber) {
                        $date = self::getLessonDate(
                            $day,
                            $subjects['startLessonTime'],
                            ($currentWeek <= $weekNumber)
                                ? ($weekNumber - $currentWeek)
                                : (4 - $currentWeek) + $weekNumber
                        );
                        $lessons[] = [
                            'date' => $date,
                            'group' => self::getSubGroup($subjects['numSubgroup']),
                            'startLessonTime' => $subjects['startLessonTime'],
                            'endLessonTime' => $subjects['endLessonTime'],
                            'day' => $day
                        ];
                    }
                }
            }
        }
        $lessons = array_filter($lessons, fn($a) => $a['date'] > date("Y-m-d H:i:s", strtotime("-2 hours")));
        usort($lessons, fn($a, $b) => strtotime($a['date']) - strtotime($b['date']));

        return $lessons;
    }

    public static function getLessons(string $group): ?array
    {
        $schedules = self::getGroupSchedule($group);
        $currentWeek = self::getCurrentWeek();

        if ($schedules && $currentWeek) {
            return self::parseSchedule($schedules, $currentWeek);
        } else {
            return null;
        }
    }
}


