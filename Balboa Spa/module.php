<?php

declare(strict_types=1);

eval('declare(strict_types=1);namespace BalboaSpa {?>' . file_get_contents(__DIR__ . '/../libs/vendor/SymconModulHelper/VariableProfileHelper.php') . '}');
eval('declare(strict_types=1);namespace BalboaSpa {?>' . file_get_contents(__DIR__ . '/../libs/vendor/SymconModulHelper/DebugHelper.php') . '}');

    class BalboaSpa extends IPSModule
    {
        use \BalboaSpa\DebugHelper;
        use \BalboaSpa\VariableProfileHelper;

        public function Create()
        {
            //Never delete this line!
            parent::Create();
            $this->ConnectParent('{C6D2AEB3-6E1F-4B2E-8E69-3A1A00246850}');

            $this->RegisterPropertyString('MQTTBaseTopic', 'homie');
            $this->RegisterPropertyString('MQTTTopic', 'bwa');

            $this->RegisterVariableBoolean('Light1', $this->Translate('Licht 1'), '~Switch', 1);
            $this->EnableAction('Light1');
            $this->RegisterVariableString('Notification', $this->Translate('Notification'), '', 2);
            $this->RegisterVariableBoolean('CirculationPump', $this->Translate('Circulation Pump'), '~Switch', 3);
            if (!IPS_VariableProfileExists('BS.CurrentTemperature')) {
                $this->RegisterProfileFloat('BS.CurrentTemperature', 'Temperature', '', ' °C', 0, 42, 1, 2);
            }

            $this->RegisterVariableFloat('CurrentTemperature', $this->Translate('Current Temperature'), 'BS.CurrentTemperature', 4);
            $this->RegisterVariableBoolean('Heating', $this->Translate('Heating'), '~Switch', 5);
            $this->EnableAction('Heating');
            if (!IPS_VariableProfileExists('BS.HeatingMode')) {
                $this->RegisterProfileStringEx('BS.HeatingMode', 'Menu', '', '', [
                    ['ready', $this->Translate('Ready'), '', 0x00ff00],
                    ['rest', $this->Translate('Rest'), '', 0xff8000],
                    ['ready_in_rest', $this->Translate('Ready in rest'), '', 0xff0000],
                ]);
            }
            $this->RegisterVariableString('HeatingMode', $this->Translate('Heating Mode'), 'BS.HeatingMode', 6);
            $this->EnableAction('HeatingMode');
            $this->RegisterVariableBoolean('Hold', $this->Translate('Hold'), '~Switch', 7);
            $this->EnableAction('Hold');
            $this->RegisterVariableBoolean('Priming', $this->Translate('Priming'), '~Switch', 8);
            $this->EnableAction('Priming');
            $this->RegisterVariableBoolean('Hold', $this->Translate('Hold'), '~Switch', 7);
            $this->EnableAction('Hold');
            $this->RegisterVariableBoolean('Pump1', $this->Translate('Jet Pump 1'), '~Switch', 8);
            $this->EnableAction('Pump1');
            $this->RegisterVariableBoolean('Pump2', $this->Translate('Jet Pump 2'), '~Switch', 9);
            $this->EnableAction('Pump2');
            if (!IPS_VariableProfileExists('BS.TargetTemperature')) {
                $this->RegisterProfileFloat('BS.TargetTemperature', 'Temperature', '', ' °C', 10, 40, 0.5, 2);
            }
            $this->RegisterVariableFloat('TargetTemperature', $this->Translate('Target Temperature'), 'BS.TargetTemperature', 10);
            $this->EnableAction('TargetTemperature');
            if (!IPS_VariableProfileExists('BS.TemperatureRange')) {
                $this->RegisterProfileStringEx('BS.TemperatureRange', 'Menu', '', '', [
                    ['high', $this->Translate('high'), '', 0x00ff00],
                    ['low', $this->Translate('low'), '', 0xff8000]
                ]);
            }
            $this->RegisterVariableString('TemperatureRange', $this->Translate('Temperature Range'), 'BS.TemperatureRange', 11);
            $this->EnableAction('TemperatureRange');
            $this->RegisterVariableBoolean('FilterCycle1Running', $this->Translate('Filter Cycle 1'), '~Switch', 12);

            if (!IPS_VariableProfileExists('BS.FilterCycleHour')) {
                $this->RegisterProfileInteger('BS.FilterCycleHour', 'Clock', '', '', 1, 24, 1);
            }
            if (!IPS_VariableProfileExists('BS.FilterCycleMinute')) {
                $this->RegisterProfileInteger('BS.FilterCycleMinute', 'Clock', '', '', 1, 60, 1);
            }
            if (!IPS_VariableProfileExists('BS.FilterCycleDuration')) {
                $this->RegisterProfileInteger('BS.FilterCycleDuration', 'Clock', '', '', 1, 1440, 1);
            }

            $this->RegisterVariableBoolean('FilterCycle1Enabled', $this->Translate('Filter Cycle 1'), '~Switch', 13);
            $this->EnableAction('FilterCycle1Enabled');
            $this->RegisterVariableInteger('FilterCycle1StartHour', $this->Translate('Filter Cycle 1 Start Hour'), 'BS.FilterCycleHour', 14);
            $this->EnableAction('FilterCycle1StartHour');
            $this->RegisterVariableInteger('FilterCycle1StartMinute', $this->Translate('Filter Cycle 1 Start Minute'), 'BS.FilterCycleMinute', 15);
            $this->EnableAction('FilterCycle1StartMinute');
            $this->RegisterVariableInteger('FilterCycle1StartDuration', $this->Translate('Filter Cycle 1 Start Duration'), 'BS.FilterCycleDuration', 16);
            $this->EnableAction('FilterCycle1StartDuration');

            $this->RegisterVariableBoolean('FilterCycle2Enabled', $this->Translate('Filter Cycle 2'), '~Switch', 17);
            $this->EnableAction('FilterCycle2Enabled');
            $this->RegisterVariableBoolean('FilterCycle2Running', $this->Translate('Filter Cycle 2'), '~Switch', 18);
            $this->RegisterVariableInteger('FilterCycle2StartHour', $this->Translate('Filter Cycle 2 Start Hour'), 'BS.FilterCycleHour', 19);
            $this->EnableAction('FilterCycle2StartHour');
            $this->RegisterVariableInteger('FilterCycle2StartMinute', $this->Translate('Filter Cycle 2 Start Minute'), 'BS.FilterCycleMinute', 20);
            $this->EnableAction('FilterCycle2StartMinute');
            $this->RegisterVariableInteger('FilterCycle2StartDuration', $this->Translate('Filter Cycle 2 Start Duration'), 'BS.FilterCycleDuration', 21);
            $this->EnableAction('FilterCycle2StartDuration');
        }

        public function Destroy()
        {
            //Never delete this line!
            parent::Destroy();
        }

        public function ApplyChanges()
        {
            //Never delete this line!
            parent::ApplyChanges();

            $Filter = preg_quote($this->ReadPropertyString('MQTTBaseTopic') . '/' . $this->ReadPropertyString('MQTTTopic'));
        }

        public function RequestAction($Ident, $Value)
        {
            switch ($Ident) {
                case 'Light1':
                    $Value = $Value ? 'true' : 'false';
                    $this->SendPayload('spa/light1/set', strval($Value));
                    break;
                case 'Heating':
                    $Value = $Value ? 'true' : 'false';
                    $this->SendPayload('spa/heating/set', strval($Value));
                    break;
                case 'HeatingMode':
                    $this->SendPayload('spa/heating-mode/set', strval($Value));
                    break;
                case 'Hold':
                    $Value = $Value ? 'true' : 'false';
                    $this->SendPayload('spa/hold/set', strval($Value));
                    break;
                case 'Pump1':
                    $Value = $Value ? 'true' : 'false';
                    $this->SendPayload('spa/pump1/set', strval($Value));
                    break;
                case 'Pump2':
                    $Value = $Value ? 'true' : 'false';
                    $this->SendPayload('spa/pump2/set', strval($Value));
                    break;
                case 'TargetTemperature':
                    $this->SendPayload('spa/target-temperature/set', strval($Value));
                    break;
                case 'TemperatureRange':
                    $this->SendPayload('spa/temperature-range/set', strval($Value));
                    break;                   
                case 'FilterCycle1Enabled':
                    $Value = $Value ? 'true' : 'false';
                    $this->SendPayload('filter-cycle1/enabled/set', strval($Value));
                    break;
                case 'FilterCycle1StartHour':
                    $this->SendPayload('filter-cycle1/start-hour/set', strval($Value));
                    break;
                case 'FilterCycle1StartMinute':
                    $this->SendPayload('filter-cycle1/start-minute/set', strval($Value));
                    break;
                case 'FilterCycle1StartDuration':
                    $this->SendPayload('filter-cycle1/duration/set', strval($Value));
                    break;
                case 'FilterCycle2Enabled':
                    $Value = $Value ? 'true' : 'false';
                    $this->SendPayload('filter-cycle2/enabled/set', strval($Value));
                    break;
                case 'FilterCycle2StartHour':
                    $this->SendPayload('filter-cycle2/start-hour/set', strval($Value));
                    break;
                case 'FilterCycle2StartMinute':
                    $this->SendPayload('filter-cycle2/start-minute/set', strval($Value));
                    break;
                case 'FilterCycle2StartDuration':
                    $this->SendPayload('filter-cycle2/duration/set', strval($Value));
                        break;
                default:
                    $this->SendDebug('RequestAction :: Invalid Ident', $Ident);
                    break;
            }
        }

        public function ReceiveData($JSONString)
        {
            if (!empty($this->ReadPropertyString('MQTTTopic'))) {
                $Buffer = json_decode($JSONString, true);
                $this->SendDebug('ReceiveData :: Buffer', $Buffer, 0);
                $Payload = $Buffer['Payload'];
                if (fnmatch('*/spa/light1', $Buffer['Topic'])) {
                    $this->SetValue('Light1', $Payload);
                }
                if (fnmatch('*/spa/notification', $Buffer['Topic'])) {
                    $this->SetValue('Notification', $Payload);
                }
                if (fnmatch('*/spa/circulation-pump', $Buffer['Topic'])) {
                    $this->SetValue('CirculationPump', $Payload);
                }
                if (fnmatch('*/spa/current-temperature', $Buffer['Topic'])) {
                    $this->SetValue('CurrentTemperature', $Payload);
                }
                if (fnmatch('*/spa/heating', $Buffer['Topic'])) {
                    $this->SetValue('Heating', $Payload);
                }
                if (fnmatch('*/spa/heating-mode', $Buffer['Topic'])) {
                    $this->SetValue('HeatingMode', $Payload);
                }
                if (fnmatch('*/spa/hold', $Buffer['Topic'])) {
                    $this->SetValue('Hold', $Payload);
                }
                if (fnmatch('*/spa/priming', $Buffer['Topic'])) {
                    $this->SetValue('Priming', $Payload);
                }
                if (fnmatch('*/spa/pump1', $Buffer['Topic'])) {
                    $this->SetValue('Pump1', $Payload);
                }
                if (fnmatch('*/spa/pump2', $Buffer['Topic'])) {
                    $this->SetValue('Pump2', $Payload);
                }
                if (fnmatch('*/spa/target-temperature', $Buffer['Topic'])) {
                    $this->SetValue('TargetTemperature', $Payload);
                }
                if (fnmatch('*/spa/temperature-range', $Buffer['Topic'])) {
                    $this->SetValue('TemperatureRange', $Payload);
                }
                if (fnmatch('*/filter-cycle1/running', $Buffer['Topic'])) {
                    $this->SetValue('FilterCycle1Running', $Payload);
                }
                if (fnmatch('*/filter-cycle1/enabled', $Buffer['Topic'])) {
                    $this->SetValue('FilterCycle1Enabled', $Payload);
                }
                if (fnmatch('*/filter-cycle1/start-hour', $Buffer['Topic'])) {
                    $this->SetValue('FilterCycle1StartHour', $Payload);
                }
                if (fnmatch('*/filter-cycle1/start-minute', $Buffer['Topic'])) {
                    $this->SetValue('FilterCycle1StartMinute', $Payload);
                }
                if (fnmatch('*/filter-cycle1/duration', $Buffer['Topic'])) {
                    $this->SetValue('FilterCycle1Duration', $Payload);
                }
                if (fnmatch('*/filter-cycle2/running', $Buffer['Topic'])) {
                    $this->SetValue('FilterCycle2Running', $Payload);
                }
                if (fnmatch('*/filter-cycle2/enabled', $Buffer['Topic'])) {
                    $this->SetValue('FilterCycle2Enabled', $Payload);
                }
                if (fnmatch('*/filter-cycle2/start-hour', $Buffer['Topic'])) {
                    $this->SetValue('FilterCycle2StartHour', $Payload);
                }
                if (fnmatch('*/filter-cycle2/start-minute', $Buffer['Topic'])) {
                    $this->SetValue('FilterCycle2StartMinute', $Payload);
                }
                if (fnmatch('*/filter-cycle2/duration', $Buffer['Topic'])) {
                    $this->SetValue('FilterCycle2Duration', $Payload);
                }
            }
        }

        private function SendPayload($topic, $payload)
        {
            $Data['DataID'] = '{043EA491-0325-4ADD-8FC2-A30C8EEB4D3F}';
            $Data['PacketType'] = 3;
            $Data['QualityOfService'] = 0;
            $Data['Retain'] = false;
            $Data['Topic'] = $this->ReadPropertyString('MQTTBaseTopic') . '/' . $this->ReadPropertyString('MQTTTopic') . '/' . $topic;
            $Data['Payload'] = $payload;
            $DataJSON = json_encode($Data, JSON_UNESCAPED_SLASHES);
            $this->SendDebug(__FUNCTION__ . ' Topic', $Data['Topic'], 0);
            $this->SendDebug(__FUNCTION__ . ' Payload', $Data['Payload'], 0);
            $this->SendDataToParent($DataJSON);
        }
    }