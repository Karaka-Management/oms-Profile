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

use phpOMS\Uri\UriFactory;

/**
 * @var \phpOMS\Views\View                $this
 * @var \Modules\Profile\Models\Profile[] $accounts
 */
$accounts = $this->data['accounts'] ?? [];

$previous = empty($accounts) ? '{/base}/profile/list' : '{/base}/profile/list?{?}&offset=' . \reset($accounts)->id . '&ptype=p';
$next     = empty($accounts) ? '{/base}/profile/list' : '{/base}/profile/list?{?}&offset=' . \end($accounts)->id . '&ptype=n';
?>
<div class="row">
    <div class="col-xs-12">
        <section class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Profiles'); ?><i class="g-icon download btn end-xs">download</i></div>
            <div class="slider">
            <table id="profileList" class="default sticky">
                <thead>
                <tr>
                    <td>
                    <td class="wf-100"><?= $this->getHtml('Name'); ?>
                        <label for="profileList-sort-1">
                            <input type="radio" name="profileList-sort" id="profileList-sort-1">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="profileList-sort-2">
                            <input type="radio" name="profileList-sort" id="profileList-sort-2">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                    <td><?= $this->getHtml('Activity'); ?>
                        <label for="profileList-sort-3">
                            <input type="radio" name="profileList-sort" id="profileList-sort-3">
                            <i class="sort-asc g-icon">expand_less</i>
                        </label>
                        <label for="profileList-sort-4">
                            <input type="radio" name="profileList-sort" id="profileList-sort-4">
                            <i class="sort-desc g-icon">expand_more</i>
                        </label>
                        <label>
                            <i class="filter g-icon">filter_alt</i>
                        </label>
                <tbody>
                <?php $count = 0;
                    foreach ($accounts as $key => $account) : ++$count;
                        $url = UriFactory::build('{/base}/profile/view?{?}&id=' . $account->id);
                ?>
                    <tr tabindex="0" data-href="<?= $url; ?>">
                        <td><a href="<?= $url; ?>"><img alt="<?= $this->getHtml('IMG_alt_user'); ?>" width="30" loading="lazy" class="profile-image"
                            src="<?= $account->image->id === 0
                                ? UriFactory::build($this->data['defaultImage']->getPath())
                                : UriFactory::build($account->image->getPath()); ?>"></a>
                        <td data-label="<?= $this->getHtml('Name'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($account->account->name3 . ' ' . $account->account->name2 . ' ' . $account->account->name1); ?></a>
                        <td data-label="<?= $this->getHtml('Activity'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($account->account->getLastActive()->format('Y-m-d')); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                    <tr><td colspan="3" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                <?php endif; ?>
            </table>
            </div>
            <!--
            <div class="portlet-foot">
                <a tabindex="0" class="button" href="<?= UriFactory::build($previous); ?>"><?= $this->getHtml('Previous', '0', '0'); ?></a>
                <a tabindex="0" class="button" href="<?= UriFactory::build($next); ?>"><?= $this->getHtml('Next', '0', '0'); ?></a>
            </div>
            -->
        </section>
    </div>
</div>
