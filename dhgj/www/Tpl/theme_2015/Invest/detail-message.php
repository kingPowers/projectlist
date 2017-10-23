<foreach name="list" item="vo">
    <li>
        <div class="c_left"><img src="_STATIC_/2015/invest/image/de_jltit.png" alt=""><p class="jl_l_pb">{$vo['username']|hide_username}</p></div>
        <div class="c_left jl_r">
            <p>{$vo['content']}</p>
            <p class="jl_date">时间：{$vo.timeadd}</p>
        </div>
    </li>
</foreach>
{__NOLAYOUT__}