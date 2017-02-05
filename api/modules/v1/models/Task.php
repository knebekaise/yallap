<?php

namespace api\modules\v1\models;

use Yii;
use api\common\models\Task as BaseTask;
use yii\helpers\FileHelper;

class Task extends BaseTask
{
    const SCENARIO_CREATE = 'create';

    public $video;

    public $fileUploadPath = 'uploads/source/';

    public function rules()
    {
        return [
            [['video', 'start_time', 'end_time'], 'safe'],
            [['video'], 'file', 'skipOnEmpty' => false],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['status'],
            self::SCENARIO_CREATE => ['video', 'start_time', 'end_time'],
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->original_file_name = $this->video->baseName . '.' . $this->video->extension;
            $this->file_name = $this->getSourceFileName() . '.' . $this->video->extension;
            $this->video->saveAs($this->getSourceFilePath());
            return true;
        } else {
            return false;
        }
    }

    public function getSourceFileName()
    {
        return uniqid(rand(), true);
    }

    public function getSourceFilePath()
    {
        return $this->getSourceDirectory() . $this->file_name;
    }

    protected function getSourceDirectory()
    {
        $path = Yii::getAlias('@api/web') . '/' . $this->fileUploadPath;
        FileHelper::createDirectory($path);
        return $path;
    }
}

