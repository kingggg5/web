<?php
if (!isset($config)) {
    exit;
}
if (!$config['map_tp']) {
    exit;
}
?>
<div class="form-group">
    <div class="btn-group" data-toggle="buttons">
        <select name="pos" id="pos" style="background-color: black;">
            <option value="6801.724 84.775 4899.516 220">Campos City</option>
            <option value="5142.875 134.825 3544.632 34">Rocky Ford</option>
            <option value="5382.821 59.318 4341.841 168">Oak mountain</option>
            <option value="2662.159 123.853 2336.681 353">Airport</option>
            <option value="2276.613 105.142 3860.261 220">Road 25</option>
            <option value="5500.059 166.667 6500.000 270">SafeZoneBlue Ridge</option>
            <option value="4002.524 45.249 4253.962 160">SafeZoneBoulder City</option>
            <option value="5330.348 136.356 2416.564 8">SafeZoneCastle pine</option>
        </select>
    </div>
</div>