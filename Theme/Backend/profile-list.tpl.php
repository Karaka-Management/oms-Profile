<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
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

$previous = empty($accounts) ? '{/prefix}profile/list' : '{/prefix}profile/list?{?}&id=' . \reset($accounts)->getId() . '&ptype=p';
$next     = empty($accounts) ? '{/prefix}profile/list' : '{/prefix}profile/list?{?}&id=' . \end($accounts)->getId() . '&ptype=n';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Profiles'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <div class="slider">
            <table id="profileList" class="default sticky">
                <thead>
                <tr>
                    <td>
                    <td class="wf-100"><?= $this->getHtml('Name'); ?>
                        <label for="profileList-sort-1">
                            <input type="radio" name="profileList-sort" id="profileList-sort-1">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="profileList-sort-2">
                            <input type="radio" name="profileList-sort" id="profileList-sort-2">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                    <td><?= $this->getHtml('Activity'); ?>
                        <label for="profileList-sort-3">
                            <input type="radio" name="profileList-sort" id="profileList-sort-3">
                            <i class="sort-asc fa fa-chevron-up"></i>
                        </label>
                        <label for="profileList-sort-4">
                            <input type="radio" name="profileList-sort" id="profileList-sort-4">
                            <i class="sort-desc fa fa-chevron-down"></i>
                        </label>
                        <label>
                            <i class="filter fa fa-filter"></i>
                        </label>
                <tbody>
                <?php $count = 0;
                    foreach ($accounts as $key => $account) : ++$count;
                        $url = UriFactory::build('{/prefix}profile/single?{?}&id=' . $account->getId());
                ?>
                    <tr tabindex="0" data-href="<?= $url; ?>">
                        <td><a href="<?= $url; ?>"><img width="30" loading="lazy" class="profile-image"
                            src="<?=
                                    $account->image instanceof NullMedia
                                        ? UriFactory::build('{/prefix}' . $this->getData('defaultImage')->getPath())
                                        : UriFactory::build('{/prefix}' . $account->image->getPath()); ?>"></a>
                        <td data-label="<?= $this->getHtml('Name'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($account->account->name3 . ' ' . $account->account->name2 . ' ' . $account->account->name1); ?></a>
                        <td data-label="<?= $this->getHtml('Activity'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($account->account->getLastActive()->format('Y-m-d')); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="3" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
            <div class="portlet-foot">
                <a tabindex="0" class="button" href="<?= UriFactory::build($previous); ?>"><?= $this->getHtml('Previous', '0', '0'); ?></a>
                <a tabindex="0" class="button" href="<?= UriFactory::build($next); ?>"><?= $this->getHtml('Next', '0', '0'); ?></a>
            </div>
        </div>
    </div>
</div>
