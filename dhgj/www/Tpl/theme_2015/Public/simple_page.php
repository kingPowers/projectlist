<div class="simple-pagination">
    <ul class="simple-pagination-container">
    <?php
        $prev = $page_vars['no'] > 1 ? '<li><a href="' . str_replace('*', $page_vars['no'] - 1, $page_url) . '">上一页</a></li>' : '<li class="disabled"><a href="javascript:;" class="ng-binding">上一页</a></li>';
        
        $next = $page_vars['no'] < $page_vars['total'] ? '<li><a href="' . str_replace('*', $page_vars['no'] + 1, $page_url) . '">下一页</a></li>' : '<li class="disabled"><a href="javascript:;" class="ng-binding">下一页</a></li>';
        
        echo $prev . $next;
    ?>
    </ul>
</div>
{__NOLAYOUT__}