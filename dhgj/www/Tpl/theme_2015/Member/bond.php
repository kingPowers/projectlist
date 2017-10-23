<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/tender.css">
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li <if condition="$status eq 1">class="now"</if>><a href="/member/bond/status/1">未转让</a></li>
            <li <if condition="$status eq 2">class="now"</if>><a href="/member/bond/status/2">正在转让</a></li>
            <li <if condition="$status eq 3">class="now"</if>><a href="/member/bond/status/3">转让成功</a></li>
        </ul>
    </div>
    <div class="tender_div">

        <if condition="$status eq 1">

            <table class="tender_table">
                <tr>
                    <th style="width:210px;">项目名称</th>
                    <th style="width:71px;">类型</th>
                    <th style="width:78px;">年利率</th>
                    <th style="width:71px;">期限</th>
                    <th style="width:100px;">投资金额</th>
                    <th style="width:92px;">总计本息</th>
                    <th style="width:94px;">到期时间</th>
                    <th style="width:106px;">转让</th>
                </tr>
                <foreach name="list" item="vo">
                <tr>
                    <td>{$vo.title}</td>
                    <td>{$vo.name}</td>
                    <td>{$vo.rate}%</td>
                    <td><if condition="$isloanday eq 1">{$vo.loanday}天<else/>{$vo.loanmonth}个月</if></td>
                    <td>￥{$vo.money}</td>
                    <td>￥{$vo['money']+$vo['ratemoney']}</td>
                    <td>{$vo.endtime|substr=0,10}</td>
                    <td><a href="javascript:;" class="transfer" attr="{$vo.tendersn}">转让</a></td>
                </tr>
                </foreach>
            </table>
        <elseif condition="$status eq 2"/>
            <table class="tender_table">
                <tr>
                    <th style="width:210px;">项目名称</th>
                    <th style="width:71px;">类型</th>
                    <th style="width:78px;">投资金额</th>
                    <th style="width:71px;">剩余天数</th>
                    <th style="width:100px;">待收日期</th>
                    <th style="width:92px;">到期日期</th>
                    <th style="width:94px;">待收本息</th>
                    <th style="width:106px;">转让</th>
                </tr>
                <foreach name="list" item="vo">
                    <tr>
                        <td>{$vo.title}</td>
                        <td>{$vo.name}</td>
                        <td>￥{$vo.money}</td>
                        <td>{$vo.difftime}</td>
                        <td>{$vo.ldays}天</td>
                        <td>{$vo.endtime|substr=0,10}</td>
                        <td>￥{$vo.dsamount}</td>
                        <td><a href="javascript:;" class="canceltransfer" attr="{$vo.tendersn}">取消</a></td>
                    </tr>
                </foreach>
            </table>
        <elseif condition="$status eq 3"/>
            <table class="tender_table">
                <tr>
                    <th style="width:210px;">项目名称</th>
                    <th style="width:71px;">类型</th>
                    <th style="width:78px;">投资金额</th>
                    <th style="width:71px;">年利率</th>
                    <th style="width:100px;">期限</th>
                    <th style="width:92px;">到期日期</th>
                    <th style="width:94px;">总计本息</th>
                    <th style="width:106px;">转让</th>
                </tr>
                <foreach name="list" item="vo">
                <tr>
                    <td>{$vo.title}</td>
                    <td>{$vo.name}</td>
                    <td>￥{$vo.money}</td>
                    <td>{$vo.yearrate}%</td>
                    <td><if condition="$isloanday eq 1">{$vo.loanday}天<else/>{$vo.loanmonth}个月</if></td>
                    <td>{$vo.endtime|substr=0,10}</td>
                    <td>￥{$vo['money']+$vo['ratemoney']}</td>
                    <td>转让成功</td>
                </tr>
                </foreach>
            </table>
        </if>
    </div>
<div>{$page}</div>

<div class="bank_explain">
    <p>* 温馨提示：</p>
    <p>1. 用户投资的标满30个自然日后，自由灵活进行债权转让。</p>
    <p>2. 0 转让费，投标只能转让一次，时间为72个小时进行债权转让。</p>
    <p>3. 转让金为投资本金加上当前未付利息。</p>
    <p>4. 当标开始转让，如72小时内未转出去（转让失败），平台自动撤销转让，此标不能再转让。</p>
</div>
<script>
    $(function(){
        //
        $('.transfer').click(function(){

            var tendersn = $(this).attr('attr');
            if(!tendersn){
                jdbox.alert(0,"投资sn为空");
                return false;
            }
            jdbox.alert(2);
            $.post('/uajax/settransfer',{tendersn:tendersn},function(R){
                jdbox.alert(R.status,R.info);
                if(R.status){
                    window.location.reload();
                }
            },'json');

        })

        $('.canceltransfer').click(function(){
            var tendersn = $(this).attr('attr');
            if(!tendersn){
                jdbox.alert(0,"投资sn为空");
                return false;
            }
            jdbox.alert(2);
            $.post('/uajax/canceltransfer',{tendersn:tendersn},function(R){
                jdbox.alert(R.status,R.info);
                if(R.status){
                    window.location.reload();
                }
            },'json');
        })
    })
</script>
<include file="Public:accountFooter"/>