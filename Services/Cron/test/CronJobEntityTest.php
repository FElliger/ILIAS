<?php declare(strict_types=1);

/* Copyright (c) 1998-2021 ILIAS open source, Extended GPL, see docs/LICENSE */

use PHPUnit\Framework\TestCase;

/**
 * Class CronJobEntityTest
 * @author Michael Jansen <mjansen@databay.de>
 */
class CronJobEntityTest extends TestCase
{
    /**
     * @param ilCronJob|null $job_instance
     * @param int $schedule_type
     * @param int $schedule_value
     * @param bool $is_plugin
     * @return ilCronJobEntity
     */
    private function getEntity(
        ilCronJob $job_instance = null,
        int $schedule_type = ilCronJob::SCHEDULE_TYPE_IN_MINUTES,
        int $schedule_value = 5,
        bool $is_plugin = false
    ) : ilCronJobEntity {
        $job_instance = $job_instance ?? $this->createMock(ilCronJob::class);

        return new ilCronJobEntity($job_instance, [
            'job_id' => 'phpunit',
            'component' => 'phpunit',
            'schedule_type' => $schedule_type,
            'schedule_value' => $schedule_value,
            'job_status' => 1,
            'job_status_user_id' => 6,
            'job_status_type' => 1,
            'job_status_ts' => time(),
            'job_result_status' => ilCronJobResult::STATUS_OK,
            'job_result_user_id' => 6,
            'job_result_code' => ilCronJobResult::CODE_NO_RESULT,
            'job_result_message' => 'msg',
            'job_result_type' => 1,
            'job_result_ts' => time(),
            'class' => 'Job',
            'path' => '/',
            'running_ts' => time(),
            'job_result_dur' => time(),
            'alive_ts' => time(),
        ], $is_plugin);
    }

    public function testEntityCollectionCanBeCreatedWithItems() : ilCronJobEntities
    {
        $entities = new ilCronJobEntities([
            $this->getEntity()
        ]);

        $this->assertCount(1, $entities->toArray());

        return $entities;
    }

    /**
     * @param ilCronJobEntities $entities
     * @return ilCronJobEntities
     * @depends testEntityCollectionCanBeCreatedWithItems
     */
    public function testCollectionCanBeChanged(ilCronJobEntities $entities) : ilCronJobEntities
    {
        $entities->add($this->getEntity());

        $this->assertCount(2, $entities->toArray());

        return $entities;
    }

    /**
     * @param ilCronJobEntities $entities
     * @depends testCollectionCanBeChanged
     */
    public function testCollectionCanBeFilteredAndSliced(ilCronJobEntities $entities) : void
    {
        $this->assertCount(0, $entities->filter(static function (ilCronJobEntity $entity) : bool {
            return $entity->getJobId() !== 'phpunit';
        }));

        $this->assertCount(1, $entities->slice(1, 1));
    }

    public function testEffectiveScheduleCanBeDetermined() : void
    {
        $job_instance = $this->createMock(ilCronJob::class);
        $job_instance->method('hasFlexibleSchedule')->willReturn(true);

        $entity = $this->getEntity($job_instance);
        $this->assertEquals(ilCronJob::SCHEDULE_TYPE_IN_MINUTES, $entity->getEffectiveScheduleType());
        $this->assertEquals(5, $entity->getEffectiveScheduleValue());

        $another_job_instance = $this->createMock(ilCronJob::class);
        $another_job_instance->method('hasFlexibleSchedule')->willReturn(false);
        $another_job_instance->method('getDefaultScheduleType')->willReturn(ilCronJob::SCHEDULE_TYPE_IN_HOURS);
        $another_job_instance->method('getDefaultScheduleValue')->willReturn(5);

        $another_entity = $this->getEntity($another_job_instance, ilCronJob::SCHEDULE_TYPE_DAILY);
        $this->assertEquals(ilCronJob::SCHEDULE_TYPE_IN_HOURS, $another_entity->getEffectiveScheduleType());
        $this->assertEquals(5, $another_entity->getEffectiveScheduleValue());

        $yet_another_job_instance = $this->createMock(ilCronJob::class);
        $yet_another_job_instance->method('hasFlexibleSchedule')->willReturn(true);
        $yet_another_job_instance->method('getDefaultScheduleType')->willReturn(ilCronJob::SCHEDULE_TYPE_IN_HOURS);
        $yet_another_job_instance->method('getDefaultScheduleValue')->willReturn(5);

        $yet_another_entity = $this->getEntity($yet_another_job_instance, 0);
        $this->assertEquals(ilCronJob::SCHEDULE_TYPE_IN_HOURS, $yet_another_entity->getEffectiveScheduleType());
        $this->assertEquals(5, $yet_another_entity->getEffectiveScheduleValue());
    }
}
