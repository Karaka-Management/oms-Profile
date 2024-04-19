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
use phpOMS\Uri\UriFactory;

/** @var \phpOMS\Views\View $this */
/** @var \Modules\Profile\Models\Profile $profile */
$profile = $this->data['account'];
$account = $profile->account;

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
                                            ? UriFactory::build($this->data['defaultImage']->getPath())
                                            : UriFactory::build($profile->image->getPath()); ?>"
                                width="100px"></div>
                                <?php if ($this->request->header->account === $profile->account->id) : ?>
                                    <div class="cT"><a id="iProfileUploadButton" href="#upload" data-action='[
                                        {"listener": "click", "key": 1, "action": [
                                            {"key": 1, "type": "event.prevent"},
                                            {"key": 2, "type": "dom.click", "selector": "#iProfileUpload"}
                                            ]
                                        }]'><?= $this->getHtml('Edit', '0', '0'); ?></a>
                                    <form id="iProfileUploadForm" action="<?= UriFactory::build('{/api}profile/settings/image?csrf={$CSRF}'); ?>" method="post"><input class="preview" data-action='[
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
        ?>
        <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
        <div class="tab">
            <?php include __DIR__ . '/../../../Admin/Theme/Backend/Components/Localization/l11n-view.tpl.php'; ?>
        </div>

        <input type="radio" id="c-tab-3" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-3' ? ' checked' : ''; ?>>
        <div class="tab">
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="portlet">
                        <form id="fPassword" name="fPassword" action="<?= UriFactory::build('{/api}profile/settings/password?csrf={$CSRF}'); ?>" method="post">
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
