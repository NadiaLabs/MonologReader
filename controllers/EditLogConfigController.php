<?php !defined('MONOLOG_READER') && die(0);

/**
 * Class EditLogConfigController
 */
class EditLogConfigController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function run(Request $request)
    {
        if ($request->isMethod('POST')) {
            return $this->processSave($request);
        }

        $key = $request->get('key');
        $logConfigs = $this->getConfig('logs');

        if (empty($logConfigs[$key])) {
            $logConfig = [
                'name' => $request->get('name'),
                'group' => $request->get('group'),
                'path' => $request->get('path'),
            ];
            $title = 'Create a new log config';
            $action = 'create';
        } else {
            $logConfig = $logConfigs[$key];
            $title = 'Edit a new log config';
            $action = 'edit';
        }

        $viewData = [
            'logConfig' => $logConfig,
            'title' => $title,
            'action' => $action,
        ];

        $session = $request->getSession();

        if ($session->has('error')) {
            $viewData['error'] = $session->get('error');

            $session->remove('error');
        }

        return $this->render($viewData);
    }

    private function processSave(Request $request)
    {
        $session = $request->getSession();
        $name = $request->get('name');
        $group = $request->get('group');
        $path = $request->get('path');
        $action = $request->get('action');
        $logConfigs = $this->getConfig('logs', []);
        $logConfigs = (empty($logConfigs) || !is_array($logConfigs)) ? [] : $logConfigs;
        $newLogConfig = compact('name', 'group', 'path');

        $result = $this->checkForm($name, $group, $path, $action, $logConfigs);

        if ($result !== true) {
            $session->set('error', $result);

            return $this->redirectController(EditLogConfigController::class, $newLogConfig);
        }

        $logConfigs[$this->getConfigKey($name, $group)] = $newLogConfig;

        $this->writeConfigFile('logs', $logConfigs);

        return $this->redirectController(IndexController::class);
    }

    private function checkForm($name, $group, $path, $action, array $logConfigs)
    {
        if (empty($name)) {
            return 'Name cannot be empty!';
        }
        if (empty($group)) {
            return 'Group cannot be empty!';
        }
        if (empty($path)) {
            return 'Path cannot be empty!';
        }

        if ('create' === $action
            && array_key_exists($this->getConfigKey($name, $group), $logConfigs)) {
            return 'The group and name is exists! Chose different group or name!';
        }

        return true;
    }

    private function getConfigKey($name, $group)
    {
        return $group.':'.$name;
    }
}
