<div class="card card-body shadow-none border mb-4" id="coatree">
    <div id="html" class="demo">
        <ul>
            <li data-jstree='{ "opened" : true }'><?php echo display('c_o_a'); ?>
                <ul>
                    <?php foreach ($accMainHead as $accHeadValue): ?>
                        <li data-jstree='{ "opened" : true }' data-id="<?php echo htmlspecialchars($accHeadValue->id); ?>">
                            <?php echo htmlspecialchars($accHeadValue->account_name); ?>

                            <?php
                            // Fetch second-level items
                            $secondLevelItems = array_filter($accSecondLableHead, function ($item) use ($accHeadValue) {
                                return $item->parent_id == $accHeadValue->id;
                            });
                            ?>
                            <?php if (!empty($secondLevelItems)): ?>
                                <ul>
                                    <?php foreach ($secondLevelItems as $accSecondHeadValue): ?>
                                        <li data-jstree='{ "selected" : false }' data-id="<?php echo htmlspecialchars($accSecondHeadValue->id); ?>">
                                            <?php echo htmlspecialchars($accSecondHeadValue->account_name); ?>

                                            <?php
                                            // Fetch third-level items
                                            $thirdLevelItems = array_filter($accHeadWithoutFandS, function ($item) use ($accSecondHeadValue) {
                                                return $item->parent_id == $accSecondHeadValue->id;
                                            });
                                            ?>
                                            <?php if (!empty($thirdLevelItems)): ?>
                                                <ul>
                                                    <?php foreach ($thirdLevelItems as $accHeadWithoutFandSValue): ?>
                                                        <li data-jstree='{ "selected" : false }' data-id="<?php echo htmlspecialchars($accHeadWithoutFandSValue->id); ?>">
                                                            <?php echo htmlspecialchars($accHeadWithoutFandSValue->account_name); ?>

                                                            <?php
                                                            // Fetch fourth-level items
                                                            $fourthLevelItems = array_filter($accHeadWithoutFandS, function ($item) use ($accHeadWithoutFandSValue) {
                                                                return $item->parent_id == $accHeadWithoutFandSValue->id;
                                                            });
                                                            ?>
                                                            <?php if (!empty($fourthLevelItems)): ?>
                                                                <ul>
                                                                    <?php foreach ($fourthLevelItems as $fourthLavleValue): ?>
                                                                        <li data-jstree='{ "selected" : false }' data-id="<?php echo htmlspecialchars($fourthLavleValue->id); ?>">
                                                                            <?php echo htmlspecialchars($fourthLavleValue->account_name); ?>
                                                                        </li>
                                                                    <?php endforeach; ?>
                                                                </ul>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        </ul>
    </div>
</div>