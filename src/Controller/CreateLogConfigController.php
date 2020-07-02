<?php

namespace MonologReader\Controller;

!defined('MONOLOG_READER') && die(0);

/**
 * Class CreateLogConfigController
 */
class CreateLogConfigController extends EditLogConfigController
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $request = $this->request;

        if ($request->isMethod('POST')) {
            return $this->save();
        }

        $viewData = [
            'logConfig' => [
                'id' => null,
                'name' => $request->get('name'),
                'path' => $request->get('path'),
            ],
            'title' => 'Add a new log configuration',
            'submitText' => 'Create',
        ];

        return $this->render('edit-log-config', $viewData);
    }
}
