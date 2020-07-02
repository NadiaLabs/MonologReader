<?php

namespace MonologReader\Controller;

use MonologReader\HttpFoundation\RedirectResponse;

!defined('MONOLOG_READER') && die(0);

/**
 * Class EditLogConfigController
 */
class EditLogConfigController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $request = $this->request;
        $id = $request->get('id');
        $logConfigs = $this->getConfigManager()->loadLogs();

        if (empty($logConfigs[$id])) {
            $request->getSession()->set('error', 'Log config is not exist!');

            return $this->redirectRoute(DashboardController::class);
        }

        if ($request->isMethod('POST')) {
            return $this->save($id);
        }

        $viewData = [
            'logConfig' => $logConfigs[$id],
            'title' => 'Edit a log configuration',
            'submitText' => 'Update',
        ];

        return $this->render('edit-log-config', $viewData);
    }

    /**
     * @param int|null $id
     *
     * @return RedirectResponse
     */
    protected function save($id = null)
    {
        $request = $this->request;
        $session = $request->getSession();
        $name = $request->get('name');
        $path = $request->get('path');
        $action = $request->get('action');

        $logConfigs = $this->getConfigManager()->loadLogs();
        $newLogConfig = compact('name', 'path');

        $result = $this->checkForm($name, $path);

        if ($result !== true) {
            $session->set('error', $result);

            if (is_null($id)) {
                return $this->redirectRoute(
                    CreateLogConfigController::class,
                    array_merge($newLogConfig, ['action' => $action])
                );
            } else {
                return $this->redirectRoute(
                    EditLogConfigController::class,
                    array_merge($newLogConfig, ['action' => $action, 'id' => $id])
                );
            }
        }

        if (is_null($id)) {
            $logConfigs[] = $newLogConfig;
        } else {
            $logConfigs[$id] = $newLogConfig;
        }

        $this->getConfigManager()->updateLogs($logConfigs);

        $session->set(
            'success',
            sprintf('%s log "%s" successfully!', is_null($id) ? 'Create' : 'Update', $name)
        );

        return $this->redirectRoute(DashboardController::class);
    }

    /**
     * @param string $name
     * @param string $path
     *
     * @return bool|string
     */
    protected function checkForm($name, $path)
    {
        if (empty($name)) {
            return 'Name cannot be empty!';
        }
        if (empty($path)) {
            return 'Path cannot be empty!';
        }
        if (!file_exists($path)) {
            return 'Log file path is not exists!';
        }

        return true;
    }
}
