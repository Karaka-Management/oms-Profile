<?php
/**
 * Orange Management
 *
 * PHP Version 7.4
 *
 * @package   Modules\Profile
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

use Modules\Media\Models\NullMedia;
use phpOMS\Uri\UriFactory;

/**
 * @var \phpOMS\Views\View                $this
 * @var \Modules\Profile\Models\Profile[] $accounts
 */
$accounts = $this->getData('accounts') ?? [];

$previous = empty($accounts) ? '{/prefix}profile/list' : '{/prefix}profile/list?{?}&id=' . \reset($accounts)->getId() . '&ptype=-';
$next     = empty($accounts) ? '{/prefix}profile/list' : '{/prefix}profile/list?{?}&id=' . \end($accounts)->getId() . '&ptype=+';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Profiles') ?><i class="fa fa-download floatRight download btn"></i></div>
            <table id="profileList" class="default">
                <thead>
                <tr>
                    <td>
                    <td class="wf-100"><?= $this->getHtml('Name') ?>
                    <td><?= $this->getHtml('Activity') ?>
                <tbody>
                <?php $count = 0; foreach ($accounts as $key => $account) : ++$count;
                $url = UriFactory::build('{/prefix}profile/single?{?}&id=' . $account->getId()); ?>
                    <tr tabindex="0" tabindex="0" data-href="<?= $url; ?>">
                        <td><a href="<?= $url; ?>"><img width="30" class="profile-image"
                            data-lazyload="<?=
                                    $account->getImage() instanceof NullMedia ?
                                        UriFactory::build('Web/Backend/img/user_default_' . \mt_rand(1, 6) .'.png') :
                                        UriFactory::build('{/prefix}' . $account->getImage()->getPath()); ?>"></a>
                        <td data-label="<?= $this->getHtml('Name') ?>"><a href="<?= $url; ?>"><?= $this->printHtml($account->getAccount()->getName3() . ' ' . $account->getAccount()->getName2() . ' ' . $account->getAccount()->getName1()); ?></a>
                        <td  data-label="<?= $this->getHtml('Activity') ?>"><a href="<?= $url; ?>"><?= $this->printHtml($account->getAccount()->getLastActive()->format('Y-m-d')); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                <tr><td colspan="3" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            <div class="portlet-foot">
                <a tabindex="0" class="button" href="<?= UriFactory::build($previous); ?>"><?= $this->getHtml('Previous', '0', '0'); ?></a>
                <a tabindex="0" class="button" href="<?= UriFactory::build($next); ?>"><?= $this->getHtml('Next', '0', '0'); ?></a>
            </div>
        </div>
    </div>
</div>
