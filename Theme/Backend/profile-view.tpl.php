<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   Modules\Profile
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.0
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

use Modules\Admin\Models\ContactType;
use phpOMS\Localization\ISO3166NameEnum;
use phpOMS\Localization\ISO3166TwoEnum;
use phpOMS\Localization\ISO4217Enum;
use phpOMS\Localization\ISO639Enum;
use phpOMS\Localization\ISO8601EnumArray;
use phpOMS\Localization\TimeZoneEnumArray;
use phpOMS\System\File\Local\Directory;
use phpOMS\Uri\UriFactory;
use phpOMS\Utils\Converter\AreaType;
use phpOMS\Utils\Converter\LengthType;
use phpOMS\Utils\Converter\SpeedType;
use phpOMS\Utils\Converter\TemperatureType;
use phpOMS\Utils\Converter\VolumeType;
use phpOMS\Utils\Converter\WeightType;

/** @var \phpOMS\Views\View $this */
/** @var \Modules\Profile\Models\Profile $profile */
$profile = $this->data['account'];

/** @var \Modules\Media\Models\Media[] $media */
$media = $this->data['media'] ?? [];

$l11n = $this->data['l11n'];

echo $this->data['nav']->render();
?>
<div class="tabview tab-2">
    <div class="box">
        <ul class="tab-links">
            <li><label for="c-tab-1"><?= $this->getHtml('Profile'); ?></label>
            <?php if ($this->request->header->account === $profile->account->id) : ?>
            <li><label for="c-tab-2"><?= $this->getHtml('Localization'); ?></label>
            <li><label for="c-tab-3"><?= $this->getHtml('Password'); ?></label>
            <?php endif; ?>
        </ul>
    </div>
    <div class="tab-content">
        <input type="radio" id="c-tab-1" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12">
                    <div class="portlet" itemscope itemtype="http://schema.org/Person" itemtype="http://schema.org/Organization">
                        <div class="portlet-head">
                            <?php if (!empty($profile->account->name3) || !empty($profile->account->name2)) : ?>
                                <span itemprop="familyName" itemprop="legalName">
                                    <?= $this->printHtml(empty($profile->account->name3) ? $profile->account->name2 : $profile->account->name3); ?></span>,
                            <?php endif; ?>
                            <span itemprop="givenName" itemprop="legalName">
                                <?= $this->printHtml($profile->account->name1); ?>
                            </span>
                        </div>
                        <div class="portlet-body">
                            <span class="rf">
                                <div><img id="preview-profileImage" class="m-profile rf"
                                    alt="<?= $this->getHtml('ProfileImage'); ?>"
                                    itemprop="logo" loading="lazy"
                                    src="<?= $profile->image->id === 0
                                            ? UriFactory::build($this->getData('defaultImage')->getPath())
                                            : UriFactory::build($profile->image->getPath()); ?>"
                                width="100px"></div>
                                <?php if ($this->request->header->account === $profile->account->id) : ?>
                                    <div class="cT"><a id="iProfileUploadButton" href="#upload" data-action='[
                                        {"listener": "click", "key": 1, "action": [
                                            {"key": 1, "type": "event.prevent"},
                                            {"key": 2, "type": "dom.click", "selector": "#iProfileUpload"}
                                            ]
                                        }]'><?= $this->getHtml('Edit', '0', '0'); ?></a>
                                    <form id="iProfileUploadForm" action="<?= UriFactory::build('{/api}profile/settings/image'); ?>" method="post"><input class="preview" data-action='[
                                        {"listener": "change", "key": 1, "action": [
                                            {"key": 1, "type": "form.submit", "selector": "#iProfileUploadForm"}
                                            ]
                                        }]' id="iProfileUpload" name="profileImage" type="file" accept="image/png,image/gif,image/jpeg" style="display: none;"></form></div>
                                <?php endif; ?>
                            </span>
                            <table class="list" style="table-layout: fixed">
                                <tr>
                                    <th><?= $this->getHtml('Birthday'); ?>
                                    <td itemprop="birthDate" itemprop="foundingDate"><?= $this->getDateTime($profile->birthday); ?>
                                <tr>
                                    <th><?= $this->getHtml('Email'); ?>
                                    <td itemprop="email"><a href="mailto:>donald.duck@email.com<"><?= $this->printHtml($profile->account->getEmail()); ?></a>
                                <tr>
                                    <th><?= $this->getHtml('Address'); ?>
                                    <td>
                                <?php
                                    $addresses = $profile->account->addresses;
                                    if (empty($addresses)) :
                                ?>
                                <tr>
                                    <th>
                                    <td><?= $this->getHtml('NoAddressSpecified'); ?>
                                <?php else: foreach($addresses as $location) : ?>
                                    <tr>
                                        <th><?= $this->getHtml('aType' . $location->type); ?>
                                        <td>
                                    <tr>
                                        <th>
                                        <td><?= $this->printHtml($location->address); ?>
                                    <tr>
                                        <th>
                                        <td><?= $this->printHtml($location->postal . ', ' . $location->city); ?>
                                    <tr>
                                        <th>
                                        <td><?= $this->printHtml(ISO3166NameEnum::getByName(ISO3166TwoEnum::getName($location->country))); ?>
                                <?php endforeach; endif; ?>
                                <tr>
                                    <th><?= $this->getHtml('Contact'); ?>
                                    <td>
                                    <?php
                                    $contacts = $profile->account->contacts;
                                    if (empty($contacts)) :
                                ?>
                                <tr>
                                    <th>
                                    <td><?= $this->getHtml('NoContactSpecified'); ?>
                                <?php else: foreach($contacts as $contact) : ?>
                                    <tr>
                                        <th><?= $this->getHtml('cType' . $contact->type); ?>
                                        <td><?= $contact->type === ContactType::WEBSITE ? '<a href="' . $contact->content . '">' : ''; ?>
                                                <?= $contact->content; ?>
                                            <?= $contact->type === ContactType::WEBSITE ? '</a>' : ''; ?>
                                <?php endforeach; endif; ?>
                                <tr>
                                    <th><?= $this->getHtml('Registered'); ?>
                                    <td><?= $this->printHtml($profile->account->createdAt->format('Y-m-d')); ?>
                                <tr>
                                    <th><?= $this->getHtml('LastLogin'); ?>
                                    <td><?= $this->printHtml($profile->account->getLastActive()->format('Y-m-d')); ?>
                                <tr>
                                    <th><?= $this->getHtml('Status'); ?>
                                    <td><span class="tag green"><?= $this->getHtml(':s' . $profile->account->status, 'Admin'); ?></span>
                            </table>
                        </div>
                        <?php if ($this->request->header->account === $profile->account->id) : ?>
                            <div class="portlet-foot"><button class="update"><?= $this->getHtml('Edit', '0', '0'); ?></button></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-xs-12">
                    <?= $this->getData('medialist')->render($media); ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <?= $this->getData('calendar')->render(null /* calendar object here */); ?>
                </div>
            </div>
        </div>
        <?php

        if ($this->request->header->account === $profile->account->id) :
            $countryCodes    = ISO3166TwoEnum::getConstants();
            $countries       = ISO3166NameEnum::getConstants();
            $timezones       = TimeZoneEnumArray::getConstants();
            $timeformats     = ISO8601EnumArray::getConstants();
            $languages       = ISO639Enum::getConstants();
            $currencies      = ISO4217Enum::getConstants();
            $l11nDefinitions = Directory::list(__DIR__ . '/../../../../phpOMS/Localization/Defaults/Definitions');

            $weights      = WeightType::getConstants();
            $speeds       = SpeedType::getConstants();
            $areas        = AreaType::getConstants();
            $lengths      = LengthType::getConstants();
            $volumes      = VolumeType::getConstants();
            $temperatures = TemperatureType::getConstants();
        ?>
        <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="portlet">
                        <form id="fLocalization" name="fLocalization" action="<?= UriFactory::build('{/api}profile/settings/localization'); ?>" method="post">
                        <div class="portlet-head"><?= $this->getHtml('Localization'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iDefaultLocalizations"><?= $this->getHtml('Defaults'); ?></label>
                                <div class="ipt-wrap wf-100">
                                    <div class="ipt-first"><select id="iDefaultLocalizations" name="localization_load">
                                            <option value="-1" selected disabled><?= $this->getHtml('Customized'); ?>
                                            <?php foreach ($l11nDefinitions as $def) : ?>
                                                <option value="<?= $this->printHtml(\explode('.', $def)[0]); ?>"><?= $this->printHtml(\explode('.', $def)[0]); ?>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="ipt-second"><input type="submit" name="loadDefaultLocalization" formaction="<?= UriFactory::build('{/api}profile/settings/localization'); ?>" value="<?= $this->getHtml('Load'); ?>"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="iCountries"><?= $this->getHtml('Country'); ?></label>
                                <select id="iCountries" name="settings_country">
                                    <?php foreach ($countryCodes as $code3 => $code2) : ?>
                                    <option value="<?= $this->printHtml($code2); ?>"<?= $this->printHtml($code2 === $l11n->country ? ' selected' : ''); ?>><?= $this->printHtml($countries[$code3]); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iLanguages"><?= $this->getHtml('Language'); ?></label>
                                <select id="iLanguages" name="settings_language">
                                    <?php foreach ($languages as $code => $language) : $code = \strtolower(\substr($code, 1)); ?>
                                    <option value="<?= $this->printHtml($code); ?>"<?= $this->printHtml($code === $l11n->language ? ' selected' : ''); ?>><?= $this->printHtml($language); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iTemperature"><?= $this->getHtml('Temperature'); ?></label>
                                <select id="iTemperature" name="settings_temperature">
                                    <?php foreach ($temperatures as $temperature) : ?>
                                    <option value="<?= $this->printHtml($temperature); ?>"<?= $this->printHtml($temperature === $l11n->getTemperature() ? ' selected' : ''); ?>><?= $this->printHtml($temperature); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="portlet-foot">
                            <input type="hidden" name="account_id" value="<?= $profile->account->id; ?>">
                            <input id="iSubmitLocalization" name="submitLocalization" type="submit" value="<?= $this->getHtml('Save', '0', '0'); ?>">
                        </div>
                        </form>
                    </div>
                </div>

                <div class="col-xs-12 col-md-4">
                    <div class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Time'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iTimezones"><?= $this->getHtml('Timezone'); ?></label>
                                <select form="fLocalization" id="iTimezones" name="settings_timezone">
                                    <?php foreach ($timezones as $timezone) : ?>
                                    <option value="<?= $this->printHtml($timezone); ?>"<?= $this->printHtml($timezone === $l11n->getTimezone() ? ' selected' : ''); ?>><?= $this->printHtml($timezone); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                            <h2><?= $this->getHtml('Timeformat'); ?></h2>
                            </div>

                            <div class="form-group">
                                <label for="iTimeformatVeryShort"><?= $this->getHtml('VeryShort'); ?></label>
                                <input form="fLocalization" id="iTimeformatVeryShort" name="settings_timeformat_vs" type="text" value="<?= $this->printHtml($l11n->getDatetime()['very_short']); ?>" placeholder="Y" required>
                            </div>

                            <div class="form-group">
                                <label for="iTimeformatShort"><?= $this->getHtml('Short'); ?></label>
                                <input form="fLocalization" id="iTimeformatShort" name="settings_timeformat_s" type="text" value="<?= $this->printHtml($l11n->getDatetime()['short']); ?>" placeholder="Y" required>
                            </div>

                            <div class="form-group">
                                <label for="iTimeformatMedium"><?= $this->getHtml('Medium'); ?></label>
                                <input form="fLocalization" id="iTimeformatMedium" name="settings_timeformat_m" type="text" value="<?= $this->printHtml($l11n->getDatetime()['medium']); ?>" placeholder="Y" required>
                            </div>

                            <div class="form-group">
                                <label for="iTimeformatLong"><?= $this->getHtml('Long'); ?></label>
                                <input form="fLocalization" id="iTimeformatLong" name="settings_timeformat_l" type="text" value="<?= $this->printHtml($l11n->getDatetime()['long']); ?>" placeholder="Y" required>
                            </div>

                            <div class="form-group">
                                <label for="iTimeformatVeryLong"><?= $this->getHtml('VeryLong'); ?></label>
                                <input form="fLocalization" id="iTimeformatVeryLong" name="settings_timeformat_vl" type="text" value="<?= $this->printHtml($l11n->getDatetime()['very_long']); ?>" placeholder="Y" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-4">
                    <div class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Numeric'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iCurrencies"><?= $this->getHtml('Currency'); ?></label>
                                <select form="fLocalization" id="iCurrencies" name="settings_currency">
                                    <?php foreach ($currencies as $code => $currency) : $code = \substr($code, 1); ?>
                                    <option value="<?= $this->printHtml($code); ?>"<?= $this->printHtml($code === $l11n->currency ? ' selected' : ''); ?>><?= $this->printHtml($currency); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><?= $this->getHtml('Currencyformat'); ?></label>
                                <select form="fLocalization" name="settings_currencyformat">
                                    <option value="0"<?= $this->printHtml($l11n->getCurrencyFormat() === '0' ? ' selected' : ''); ?>><?= $this->getHtml('Amount') , ' ' , $this->printHtml($l11n->currency); ?>
                                    <option value="1"<?= $this->printHtml($l11n->getCurrencyFormat() === '1' ? ' selected' : ''); ?>><?= $this->printHtml($l11n->currency) , ' ' , $this->getHtml('Amount'); ?>
                                </select>
                            </div>

                            <div class="form-group">
                            <h2><?= $this->getHtml('Numberformat'); ?></h2>
                            </div>

                            <div class="flex-line">
                                <div>
                                    <div class="form-group">
                                        <label for="iDecimalPoint"><?= $this->getHtml('DecimalPoint'); ?></label>
                                        <input form="fLocalization" id="iDecimalPoint" name="settings_decimal" type="text" value="<?= $this->printHtml($l11n->getDecimal()); ?>" placeholder="." required>
                                    </div>
                                </div>

                                <div>
                                    <div class="form-group">
                                        <label for="iThousandSep"><?= $this->getHtml('ThousandsSeparator'); ?></label>
                                        <input form="fLocalization" id="iThousandSep" name="settings_thousands" type="text" value="<?= $this->printHtml($l11n->getThousands()); ?>" placeholder="," required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-4">
                    <div class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Precision'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iPrecisionVeryShort"><?= $this->getHtml('VeryShort'); ?></label>
                                <input form="fLocalization" id="iPrecisionVeryShort" name="settings_precision_vs" value="<?= $l11n->getPrecision()['very_short']; ?>" type="number">
                            </div>

                            <div class="form-group">
                                <label for="iPrecisionShort"><?= $this->getHtml('Short'); ?></label>
                                <input form="fLocalization" id="iPrecisionLight" name="settings_precision_s" value="<?= $l11n->getPrecision()['short']; ?>" type="number">
                            </div>

                            <div class="form-group">
                                <label for="iPrecisionMedium"><?= $this->getHtml('Medium'); ?></label>
                                <input form="fLocalization" id="iPrecisionMedium" name="settings_precision_m" value="<?= $l11n->getPrecision()['medium']; ?>" type="number">
                            </div>

                            <div class="form-group">
                                <label for="iPrecisionLong"><?= $this->getHtml('Long'); ?></label>
                                <input form="fLocalization" id="iPrecisionLong" name="settings_precision_l" value="<?= $l11n->getPrecision()['long']; ?>" type="number">
                            </div>

                            <div class="form-group">
                                <label for="iPrecisionVeryLong"><?= $this->getHtml('VeryLong'); ?></label>
                                <input form="fLocalization" id="iPrecisionVeryLong" name="settings_precision_vl" value="<?= $l11n->getPrecision()['very_long']; ?>" type="number">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-4">
                    <div class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Weight'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iWeightVeryLight"><?= $this->getHtml('VeryLight'); ?></label>
                                <select form="fLocalization" id="iWeightVeryLight" name="settings_weight_vl">
                                    <?php foreach ($weights as $code => $weight) : ?>
                                    <option value="<?= $this->printHtml($weight); ?>"<?= $this->printHtml($weight === $l11n->getWeight()['very_light'] ? ' selected' : ''); ?>><?= $this->printHtml($weight); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iWeightLight"><?= $this->getHtml('Light'); ?></label>
                                <select form="fLocalization" id="iWeightLight" name="settings_weight_l">
                                    <?php foreach ($weights as $code => $weight) : ?>
                                    <option value="<?= $this->printHtml($weight); ?>"<?= $this->printHtml($weight === $l11n->getWeight()['light'] ? ' selected' : ''); ?>><?= $this->printHtml($weight); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iWeightMedium"><?= $this->getHtml('Medium'); ?></label>
                                <select form="fLocalization" id="iWeightMedium" name="settings_weight_m">
                                    <?php foreach ($weights as $code => $weight) : ?>
                                    <option value="<?= $this->printHtml($weight); ?>"<?= $this->printHtml($weight === $l11n->getWeight()['medium'] ? ' selected' : ''); ?>><?= $this->printHtml($weight); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iWeightHeavy"><?= $this->getHtml('Heavy'); ?></label>
                                <select form="fLocalization" id="iWeightHeavy" name="settings_weight_h">
                                    <?php foreach ($weights as $code => $weight) : ?>
                                    <option value="<?= $this->printHtml($weight); ?>"<?= $this->printHtml($weight === $l11n->getWeight()['heavy'] ? ' selected' : ''); ?>><?= $this->printHtml($weight); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iWeightVeryHeavy"><?= $this->getHtml('VeryHeavy'); ?></label>
                                <select form="fLocalization" id="iWeightVeryHeavy" name="settings_weight_vh">
                                    <?php foreach ($weights as $code => $weight) : ?>
                                    <option value="<?= $this->printHtml($weight); ?>"<?= $this->printHtml($weight === $l11n->getWeight()['very_heavy'] ? ' selected' : ''); ?>><?= $this->printHtml($weight); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-4">
                    <div class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Speed'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iSpeedVerySlow"><?= $this->getHtml('VerySlow'); ?></label>
                                <select form="fLocalization" id="iSpeedVerySlow" name="settings_speed_vs">
                                    <?php foreach ($speeds as $code => $speed) : ?>
                                    <option value="<?= $this->printHtml($speed); ?>"<?= $this->printHtml($speed === $l11n->getSpeed()['very_slow'] ? ' selected' : ''); ?>><?= $this->printHtml($speed); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iSpeedSlow"><?= $this->getHtml('Slow'); ?></label>
                                <select form="fLocalization" id="iSpeedSlow" name="settings_speed_s">
                                    <?php foreach ($speeds as $code => $speed) : ?>
                                    <option value="<?= $this->printHtml($speed); ?>"<?= $this->printHtml($speed === $l11n->getSpeed()['slow'] ? ' selected' : ''); ?>><?= $this->printHtml($speed); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iSpeedMedium"><?= $this->getHtml('Medium'); ?></label>
                                <select form="fLocalization" id="iSpeedMedium" name="settings_speed_m">
                                    <?php foreach ($speeds as $code => $speed) : ?>
                                    <option value="<?= $this->printHtml($speed); ?>"<?= $this->printHtml($speed === $l11n->getSpeed()['medium'] ? ' selected' : ''); ?>><?= $this->printHtml($speed); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iSpeedFast"><?= $this->getHtml('Fast'); ?></label>
                                <select form="fLocalization" id="iSpeedFast" name="settings_speed_f">
                                    <?php foreach ($speeds as $code => $speed) : ?>
                                    <option value="<?= $this->printHtml($speed); ?>"<?= $this->printHtml($speed === $l11n->getSpeed()['fast'] ? ' selected' : ''); ?>><?= $this->printHtml($speed); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iSpeedVeryFast"><?= $this->getHtml('VeryFast'); ?></label>
                                <select form="fLocalization" id="iSpeedVeryFast" name="settings_speed_vf">
                                    <?php foreach ($speeds as $code => $speed) : ?>
                                    <option value="<?= $this->printHtml($speed); ?>"<?= $this->printHtml($speed === $l11n->getSpeed()['very_fast'] ? ' selected' : ''); ?>><?= $this->printHtml($speed); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iSpeedSea"><?= $this->getHtml('Sea'); ?></label>
                                <select form="fLocalization" id="iSpeedSea" name="settings_speed_sea">
                                    <?php foreach ($speeds as $code => $speed) : ?>
                                    <option value="<?= $this->printHtml($speed); ?>"<?= $this->printHtml($speed === $l11n->getSpeed()['sea'] ? ' selected' : ''); ?>><?= $this->printHtml($speed); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-4">
                    <div class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Length'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iLengthVeryShort"><?= $this->getHtml('VeryShort'); ?></label>
                                <select form="fLocalization" id="iLengthVeryShort" name="settings_length_vs">
                                    <?php foreach ($lengths as $code => $length) : ?>
                                    <option value="<?= $this->printHtml($length); ?>"<?= $this->printHtml($length === $l11n->getLength()['very_short'] ? ' selected' : ''); ?>><?= $this->printHtml($length); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iLengthShort"><?= $this->getHtml('Short'); ?></label>
                                <select form="fLocalization" id="iLengthShort" name="settings_length_s">
                                    <?php foreach ($lengths as $code => $length) : ?>
                                    <option value="<?= $this->printHtml($length); ?>"<?= $this->printHtml($length === $l11n->getLength()['short'] ? ' selected' : ''); ?>><?= $this->printHtml($length); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iLengthMedium"><?= $this->getHtml('Medium'); ?></label>
                                <select form="fLocalization" id="iLengthMedium" name="settings_length_m">
                                    <?php foreach ($lengths as $code => $length) : ?>
                                    <option value="<?= $this->printHtml($length); ?>"<?= $this->printHtml($length === $l11n->getLength()['medium'] ? ' selected' : ''); ?>><?= $this->printHtml($length); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iLengthLong"><?= $this->getHtml('Long'); ?></label>
                                <select form="fLocalization" id="iLengthLong" name="settings_length_l">
                                    <?php foreach ($lengths as $code => $length) : ?>
                                    <option value="<?= $this->printHtml($length); ?>"<?= $this->printHtml($length === $l11n->getLength()['long'] ? ' selected' : ''); ?>><?= $this->printHtml($length); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iLengthVeryLong"><?= $this->getHtml('VeryLong'); ?></label>
                                <select form="fLocalization" id="iLengthVeryLong" name="settings_length_vl">
                                    <?php foreach ($lengths as $code => $length) : ?>
                                    <option value="<?= $this->printHtml($length); ?>"<?= $this->printHtml($length === $l11n->getLength()['very_long'] ? ' selected' : ''); ?>><?= $this->printHtml($length); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iLengthSea"><?= $this->getHtml('Sea'); ?></label>
                                <select form="fLocalization" id="iLengthSea" name="settings_length_sea">
                                    <?php foreach ($lengths as $code => $length) : ?>
                                    <option value="<?= $this->printHtml($length); ?>"<?= $this->printHtml($length === $l11n->getLength()['sea'] ? ' selected' : ''); ?>><?= $this->printHtml($length); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-4">
                    <div class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Area'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iAreaVerySmall"><?= $this->getHtml('VerySmall'); ?></label>
                                <select form="fLocalization" id="iAreaVerySmall" name="settings_area_vs">
                                    <?php foreach ($areas as $code => $area) : ?>
                                    <option value="<?= $this->printHtml($area); ?>"<?= $this->printHtml($area === $l11n->getArea()['very_small'] ? ' selected' : ''); ?>><?= $this->printHtml($area); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iAreaSmall"><?= $this->getHtml('Small'); ?></label>
                                <select form="fLocalization" id="iAreaSmall" name="settings_area_s">
                                    <?php foreach ($areas as $code => $area) : ?>
                                    <option value="<?= $this->printHtml($area); ?>"<?= $this->printHtml($area === $l11n->getArea()['small'] ? ' selected' : ''); ?>><?= $this->printHtml($area); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iAreaMedium"><?= $this->getHtml('Medium'); ?></label>
                                <select form="fLocalization" id="iAreaMedium" name="settings_area_m">
                                    <?php foreach ($areas as $code => $area) : ?>
                                    <option value="<?= $this->printHtml($area); ?>"<?= $this->printHtml($area === $l11n->getArea()['medium'] ? ' selected' : ''); ?>><?= $this->printHtml($area); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iAreaLarge"><?= $this->getHtml('Large'); ?></label>
                                <select form="fLocalization" id="iAreaLarge" name="settings_area_l">
                                    <?php foreach ($areas as $code => $area) : ?>
                                    <option value="<?= $this->printHtml($area); ?>"<?= $this->printHtml($area === $l11n->getArea()['large'] ? ' selected' : ''); ?>><?= $this->printHtml($area); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iAreaVeryLarge"><?= $this->getHtml('VeryLarge'); ?></label>
                                <select form="fLocalization" id="iAreaVeryLarge" name="settings_area_vl">
                                    <?php foreach ($areas as $code => $area) : ?>
                                    <option value="<?= $this->printHtml($area); ?>"<?= $this->printHtml($area === $l11n->getArea()['very_large'] ? ' selected' : ''); ?>><?= $this->printHtml($area); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-4">
                    <div class="portlet">
                        <div class="portlet-head"><?= $this->getHtml('Volume'); ?></div>
                        <div class="portlet-body">
                            <div class="form-group">
                                <label for="iVolumeVerySmall"><?= $this->getHtml('VerySmall'); ?></label>
                                <select form="fLocalization" id="iVolumeVerySmall" name="settings_volume_vs">
                                    <?php foreach ($volumes as $code => $volume) : ?>
                                    <option value="<?= $this->printHtml($volume); ?>"<?= $this->printHtml($volume === $l11n->getVolume()['very_small'] ? ' selected' : ''); ?>><?= $this->printHtml($volume); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iVolumeSmall"><?= $this->getHtml('Small'); ?></label>
                                <select form="fLocalization" id="iVolumeSmall" name="settings_volume_s">
                                    <?php foreach ($volumes as $code => $volume) : ?>
                                    <option value="<?= $this->printHtml($volume); ?>"<?= $this->printHtml($volume === $l11n->getVolume()['small'] ? ' selected' : ''); ?>><?= $this->printHtml($volume); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iVolumeMedium"><?= $this->getHtml('Medium'); ?></label>
                                <select form="fLocalization" id="iVolumeMedium" name="settings_volume_m">
                                    <?php foreach ($volumes as $code => $volume) : ?>
                                    <option value="<?= $this->printHtml($volume); ?>"<?= $this->printHtml($volume === $l11n->getVolume()['medium'] ? ' selected' : ''); ?>><?= $this->printHtml($volume); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iVolumeLarge"><?= $this->getHtml('Large'); ?></label>
                                <select form="fLocalization" id="iVolumeLarge" name="settings_volume_l">
                                    <?php foreach ($volumes as $code => $volume) : ?>
                                    <option value="<?= $this->printHtml($volume); ?>"<?= $this->printHtml($volume === $l11n->getVolume()['large'] ? ' selected' : ''); ?>><?= $this->printHtml($volume); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iVolumeVeryLarge"><?= $this->getHtml('VeryLarge'); ?></label>
                                <select form="fLocalization" id="iVolumeVeryLarge" name="settings_volume_vl">
                                    <?php foreach ($volumes as $code => $volume) : ?>
                                    <option value="<?= $this->printHtml($volume); ?>"<?= $this->printHtml($volume === $l11n->getVolume()['very_large'] ? ' selected' : ''); ?>><?= $this->printHtml($volume); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iVolumeTeaspoon"><?= $this->getHtml('Teaspoon'); ?></label>
                                <select form="fLocalization" id="iVolumeTeaspoon" name="settings_volume_teaspoon">
                                    <?php foreach ($volumes as $code => $volume) : ?>
                                    <option value="<?= $this->printHtml($volume); ?>"<?= $this->printHtml($volume === $l11n->getVolume()['teaspoon'] ? ' selected' : ''); ?>><?= $this->printHtml($volume); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iVolumeTablespoon"><?= $this->getHtml('Tablespoon'); ?></label>
                                <select form="fLocalization" id="iVolumeTablespoon" name="settings_volume_tablespoon">
                                    <?php foreach ($volumes as $code => $volume) : ?>
                                    <option value="<?= $this->printHtml($volume); ?>"<?= $this->printHtml($volume === $l11n->getVolume()['tablespoon'] ? ' selected' : ''); ?>><?= $this->printHtml($volume); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="iVolumeGlass"><?= $this->getHtml('Glass'); ?></label>
                                <select form="fLocalization" id="iVolumeGlass" name="settings_volume_glass">
                                    <?php foreach ($volumes as $code => $volume) : ?>
                                    <option value="<?= $this->printHtml($volume); ?>"<?= $this->printHtml($volume === $l11n->getVolume()['glass'] ? ' selected' : ''); ?>><?= $this->printHtml($volume); ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <input type="radio" id="c-tab-3" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-3' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="portlet">
                        <form id="fPassword" name="fPassword" action="<?= UriFactory::build('{/api}profile/settings/password'); ?>" method="post">
                            <div class="portlet-head"><?= $this->getHtml('Password'); ?></div>
                            <div class="portlet-body">
                                <div class="form-group">
                                    <label for="iOldPass"><?= $this->getHtml('OldPassword'); ?></label>
                                    <input id="iOldPass" name="oldpass" type="password" required>
                                </div>

                                <div class="form-group">
                                    <label for="iNewPass"><?= $this->getHtml('NewPassword'); ?></label>
                                    <input id="iNewPass" name="newpass" type="password" required>
                                </div>

                                <div class="form-group">
                                    <label for="iRepPass"><?= $this->getHtml('RepeatPassword'); ?></label>
                                    <input id="iRepPass" name="reppass" type="password" required>
                                </div>
                            </div>
                            <div class="portlet-foot">
                                <input type="submit" name="saveButton" id="iSavePassButton" value="<?= $this->getHtml('Save', '0', '0'); ?>">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
