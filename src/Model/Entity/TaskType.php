<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TaskType Entity.
 *
 * @property int $id
 * @property string $name
 * @property int $sort_order
 * @property bool $default_value
 * @property bool $active
 * @property \App\Model\Entity\Task[] $tasks
 * @property \App\Model\Entity\UserReport[] $user_reports
 */
class TaskType extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
