<template id="acc-grp-tpl">
    <section id="acc-grp" class="box w-50" style="z-index: 9; position: absolute; margin: 0 auto; left: 50%; top: 50%; transform: translate(-50%, -50%);">
        <header><h1><?= $this->getHtml('Account/Group', 'Admin'); ?></h1></header>

        <div class="inner">
        <div class="tabview tab-2">
            <div class="box wf-100">
                <ul class="tab-links">
                    <li><label for="c-tab-1"><?= $this->getHtml('Account', 'Admin'); ?></label>
                    <li><label for="c-tab-2"><?= $this->getHtml('Group', 'Admin'); ?></label>
                </ul>
            </div>
            <div class="tab-content">
                <input type="radio" id="c-tab-1" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-1' ? ' checked' : ''; ?>>
                <div class="tab">
                    <label for="iSearchAcc"><?= $this->getHtml('Search'); ?></label>
                    <input type="text" id="iSearchAcc" name="receiver-search" data-action='[
                        {
                            "key": 1, "listener": "keyup", "action": [
                                {"key": 1, "type": "utils.timer", "id": "iSearchAcc", "delay": 500, "resets": true},
                                {"key": 2, "type": "dom.table.clear", "id": "acc-table"},
                                {"key": 3, "type": "message.request", "uri": "{/base}/{/lang}/api/admin/find/account?search={!#iSearchAcc}", "method": "GET", "request_type": "json"},
                                {"key": 4, "type": "dom.table.append", "id": "acc-table", "aniIn": "fadeIn", "data": [], "bindings": {"id": "id", "name": "name/0"}, "position": -1}
                            ]
                        }
                    ]' autocomplete="off">
                    <table id="acc-table" class="popupTable">
                        <thead>
                            <tr>
                                <th data-name="id">ID
                                <th data-name="name">Name
                                <th data-name="address">Address
                                <th data-name="city">City
                                <th data-name="zip">Zip
                                <th data-name="country">Country
                                <!-- @todo get data from tr in action and pass it to next actions, or make new request based on table cell? -->
                        <tbody data-action='[
                            {
                                "key": 1, "listener": "click", "selector": "#acc-table tbody tr", "action": [
                                {"key": 1, "type": "dom.getvalue", "base": "self", "selector": ""},
                                {"key": 2, "type": "dom.setvalue", "overwrite": false, "selector": "#{$id}-idlist", "value": "{0/id}", "data": ""},
                                {"key": 3, "type": "dom.setvalue", "overwrite": false, "selector": "#{$id}-taglist", "value": "<span id=\"{$id}-taglist-{0/id}\" class=\"tag red\" data-id=\"{0/id}\"><i class=\"g-icon\">close</i> {0/name/0}, {0/name/1}<span>", "data": ""},
                                {"key": 4, "type": "dom.setvalue", "overwrite": true, "selector": "#{$id}", "value": "", "data": ""}
                                ]
                            }
                        ]'>
                        <tfoot>
                    </table>
                    <button type="button" id="iSearchAcc-close" data-action='[
                            {
                                "key": 1, "listener": "click", "action": [
                                    {"key": 1, "type": "dom.remove", "selector": "#acc-grp", "aniOut": "fadeOut"}
                                ]
                            }
                        ]'><?= $this->getHtml('Close', 'Admin'); ?></button>
                </div>
                <input type="radio" id="c-tab-2" name="tabular-2"<?= $this->request->uri->fragment === 'c-tab-2' ? ' checked' : ''; ?>>
                <div class="tab">
                    <label for="iSearchGrp"><?= $this->getHtml('Search'); ?></label>
                    <input type="text" id="iSearchGrp" name="receiver-search" data-action='[
                        {
                            "key": 1, "listener": "keyup", "action": [
                                {"key": 1, "type": "utils.timer", "id": "iSearchGrp", "delay": 500, "resets": true},
                                {"key": 2, "type": "dom.table.clear", "id": "grp-table"},
                                {"key": 3, "type": "message.request", "uri": "{/base}/{/lang}/api/admin/find/account?search={!#iSearchGrp}", "method": "GET", "request_type": "json"},
                                {"key": 4, "type": "dom.table.append", "id": "grp-table", "aniIn": "fadeIn", "data": [], "bindings": {"id": "id", "name": "name/0"}, "position": -1}
                            ]
                        }
                    ]' autocomplete="off">
                    <table id="grp-table" class="popupTable">
                        <thead>
                            <tr>
                                <th data-name="id">ID
                                <th data-name="name">Name
                                <th data-name="parent">Parent
                        <tbody>
                        <tfoot>
                    </table>
                    <button type="button" id="iSearchAcc-close" data-action='[
                            {
                                "key": 1, "listener": "click", "action": [
                                    {"key": 1, "type": "dom.remove", "selector": "#acc-grp", "aniOut": "fadeOut"}
                                ]
                            }
                        ]'><?= $this->getHtml('Close', 'Admin'); ?></button>
                </div>
            </div>
        </div>
    </section>
</template>