<?php

class GoogleSheetsMultipleProcessor extends modProcessor
{

    /**
     * @return array|string
     */
    public function process()
    {
        if (!$method = $this->getProperty('method', false)) {
            return $this->failure();
        }
        $ids = json_decode($this->properties['ids'], true);
        if (empty($ids)) {
            return $this->success();
        }

        /** @var GoogleSheets $googlesheets */
        if ($this->modx->services instanceof MODX\Revolution\Services\Container) {
            $googlesheets = $this->modx->services->get('googlesheets');
        } else {
            $googlesheets = $this->modx->getService('googlesheets', 'GoogleSheets', MODX_CORE_PATH . 'components/googlesheets/model/');
        }

        foreach ($ids as $id) {
            /** @var modProcessorResponse $response */
            $response = $googlesheets->runProcessor('mgr/' . $method, ['id' => $id]);
            if ($response->isError()) {
                return $response->getMessage();
            }
        }

        return $this->success('', $response->getResponse());
    }

}

return 'GoogleSheetsMultipleProcessor';