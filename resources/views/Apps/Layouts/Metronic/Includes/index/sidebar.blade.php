<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="start active ">
                <a href="<?php echo $_config_base_url; ?>">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <?php if (isset($_menu_backend) && !empty($_menu_backend)): ?>
                <?php foreach ($_menu_backend AS $keyword => $values): ?>
                    <li>
                        <a href="<?php echo $_config_base_url . '/' . $values['href']; ?>">
                            <i class="icon-basket"></i>
                            <span class="title"><?php echo $values['text']; ?></span>
                            <?php if (isset($values['nodes']) && !empty($values['nodes'])): ?>
                                <span class="arrow "></span>
                            <?php endif; ?>
                        </a>
                        <?php if (isset($values['nodes']) && !empty($values['nodes'])): ?>
                            <ul class="sub-menu">
                                <?php foreach ($values['nodes'] AS $key => $val): ?>
                                    <li>
                                        <a href="<?php echo $_config_base_url . '/' . $val['href']; ?>">
                                            <i class="icon-basket"></i>
                                            <span class="title"><?php echo $val['text']; ?></span>
                                            <?php if (isset($val['nodes']) && !empty($val['nodes'])): ?>
                                                <span class="arrow "></span>
                                            <?php endif; ?>
                                        </a>
                                        <?php if (isset($val['nodes']) && !empty($val['nodes'])): ?>
                                            <ul class="sub-menu">
                                                <?php foreach ($val['nodes'] AS $k => $v): ?>
                                                    <li>
                                                        <a href="<?php echo $_config_base_url . '/' . $v['href']; ?>">
                                                            <i class="icon-basket"></i>
                                                            <span class="title"><?php echo $v['text']; ?></span>
                                                            <?php if (isset($v['nodes']) && !empty($v['nodes'])): ?>
                                                                <span class="arrow "></span>
                                                            <?php endif; ?>
                                                        </a>
                                                        <?php if (isset($v['nodes']) && !empty($v['nodes'])): ?>
                                                            <ul class="sub-menu">
                                                                <?php foreach ($v['nodes'] AS $l => $w): ?>
                                                                    <li>
                                                                        <a href="<?php echo $_config_base_url . '/' . $w['href']; ?>">
                                                                            <i class="icon-basket"></i>
                                                                            <span class="title"><?php echo $w['text']; ?></span>
                                                                            <?php if (isset($w['nodes']) && !empty($w['nodes'])): ?>
                                                                                <span class="arrow "></span>
                                                                            <?php endif; ?>
                                                                        </a>
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
            <?php endif; ?>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
</div>