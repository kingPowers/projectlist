<div class="ui-filtrate-wp">
	<div class="ui-filtrate">
		<h3>项目筛选</h3>
		<volist name="menu" id="vo" key="k">
		<dl class="ui-filtrate-item <eq name="mod" value="0">ui-left<else/>ui-right</eq>">
			<dt>{$vo['name']}</dt>
			<dd><foreach name="vo['menu']" item="v"><a href="/zf-{$v['url']}.html" <eq name="v['select']" value="1">class="active"</eq>>{$v['html']}</a></foreach></dd>
		</dl>
		</volist>
	</div>
</div>
<div class="ui-container ui-mt20 ui-clearfix">
	<div class="ui-panel">
		<h2 class="ui-financial-sort">
			<span>投资项目</span>
			<span>排序</span>
			<a href="/zf-{:implode('-',$params)}-0-1.html" <elt name="_GET['sort']" value="0">class="current"</elt>>默认排序</a>
			<?php if($_GET['sort'] == 1):?>
			<a href="/zf-{:implode('-',$params)}-2-1.html" class="current">投资金额<em class="ui-icon sortdown"></em></a>
			<?php elseif($_GET['sort'] == 2):?>
			<a href="/zf-{:implode('-',$params)}-1-1.html" class="current">投资金额<em class="ui-icon sortup"></em></a>
			<?php else:?>
			<a href="/zf-{:implode('-',$params)}-1-1.html">投资金额<em class="ui-icon sortdown"></em></a>
			<?php endif;?>
			<?php if($_GET['sort'] == 3):?>
			<a href="/zf-{:implode('-',$params)}-4-1.html" class="current">投资期限<em class="ui-icon sortdown"></em></a>
			<?php elseif($_GET['sort'] == 4):?>
			<a href="/zf-{:implode('-',$params)}-3-1.html" class="current">投资期限<em class="ui-icon sortup"></em></a>
			<?php else:?>
			<a href="/zf-{:implode('-',$params)}-3-1.html">投资期限<em class="ui-icon sortdown"></em></a>
			<?php endif;?>
		</h2>


        <!--添加代码-->
        <if condition="$loan_group">
            <div class="detaile_list">
                <div class="detaile_list_one">
                    <h4 class="biaoti">{$loan_group['title']}</h4>
                    <p class="time"><if condition="$loan_group['loanstatus'] eq 0">开启时间：{$loan_group['starttime']|date="Y-m-d H:i:s",###}</if></p>
                </div>
                <div class="detaile_list_two">
                    <span>预期年化利率 <b style="font-size:24px;color:#ea5618">{$loan_group['yearrate']}</b>%</span><span>投资期限 <b style="font-size:24px;color:#31c1b0">{$loan_group['loanmonth']}</b>个月</span> <span>投资金额 <b style="font-size:24px;color:#ffa200">{$loan_group['loanmoney']}</b>元</span>
                    <if condition="$loan_group['loanstatus'] eq 1">
                        <a href="/zf-{$loan_group['loansn']}.html" target="_blank" class="ui-button ui-bg-brown bot">立即投资</a>
                    <elseif condition="$loan_group['loanstatus'] eq 3" />
                        <a href="/zf-{$loan_group['loansn']}.html" target="_blank" class="ui-button ui-bg-brown bot">招标完成</a>
                    <elseif condition="$loan_group['loanstatus'] eq 4" />
                        <a href="/zf-{$loan_group['loansn']}.html" target="_blank" class="ui-button ui-bg-brown bot" disabled="disabled">还款中</a>
                    <elseif condition="$loan_group['loanstatus'] eq 5" />
                        <a href="/zf-{$loan_group['loansn']}.html" target="_blank" class="ui-button ui-bg-brown bot" disabled="disabled">已还款</a>
                    <elseif condition="$loan_group['loanstatus'] eq 0" />
                        <a href="/zf-{$loan_group['loansn']}.html" target="_blank" class="ui-button ui-bg-brown bot" disabled="disabled">暂未开始</a>
                    </if>
                </div>
            </div>
        </if>
        
        <!--添加代码-->
		<dl class="ui-financial-list">
			<foreach name="list" item="vo">
			<dd title="{$vo['title']}">
                <if condition="$vo['is_group'] gt 0"><img class="uilabel" src="_STATIC_/2015/img/bt.png"></if>
				<a href="/zf-{$vo['loansn']}.html" target="_blank"><img class="ui-left" style="width:94px;height:80px;" src="_STATIC_/{$vo.slt_path}"></a>
				<div class="ui-left">
					<span class="li1">
						<a href="/zf-{$vo['loansn']}.html" target="_blank"><img src="_STATIC_/2015/img/zcd_logo.png" style="position: relative;top:-3px"/>&nbsp;&nbsp;{$vo['title']}</a>
						<span><em class="ui-icon repayment"></em>{$repayment[$vo['repayment']]}</span>
					</span>
					<span class="li2">预期年化利率<em class="ui-text-red">{:number_format($vo['yearrate'],2)}</em>%
                        <notempty name="vo.redpacket"><span class="ui-product-redpacket" style="color:#FFF;float:right;margin-right:5px;">红包</span></notempty>
                        <if condition="($new_friend['startdate'] elt $nowtmie) AND ($new_friend['enddate'] egt $nowtmie)">
                            <if condition="$vo['loanstatus'] eq 1">
                                <span class="ui-product-redpacket" style="color:#FFF;float:right;margin-right:5px;">活动</span>
                            <elseif condition="($vo['yearrate'] eq 16) or ($vo['yearrate'] eq 18)"/>
                                <span class="ui-product-redpacket" style="color:#FFF;float:right;margin-right:5px;">活动</span>
                            </if>
                        </if>
                    </span>
					<span class="li3">投资期限<eq name="vo['isloanday']" value="1"><em  class="ui-text-peagreen">{$vo['loanday']}</em>天<else/><em  class="ui-text-peagreen">{$vo['loanmonth']}</em>个月</eq></span>
					<span class="li4">投资金额<em class="ui-text-orange">{:number_format($vo['loanmoney'],2)}</em>元</span>
				</div>
				<div class="ui-right">
					<span class="li5">
						<div>完成进度：{$vo['tenderspeed']}%</div>
						<div class="ui-meter-line"><span style="width:{$vo['tenderspeed']}%"></span></div>
					</span>
					<span class="li6">
					<if condition="$vo['loanstatus'] eq 1">
						<a href="/zf-{$vo['loansn']}.html" target="_blank" class="ui-button ui-bg-brown">立即投资</a>
					<elseif condition="$vo['loanstatus'] eq 3" />
						<a href="/zf-{$vo['loansn']}.html" target="_blank" class="ui-button ui-bg-brown" disabled="disabled">招标完成</a>
					<elseif condition="$vo['loanstatus'] eq 4" />
						<a href="/zf-{$vo['loansn']}.html" target="_blank" class="ui-button ui-bg-brown" disabled="disabled">还款中</a>
					<elseif condition="$vo['loanstatus'] eq 5" />
						<a href="/zf-{$vo['loansn']}.html" target="_blank" class="ui-button ui-bg-brown" disabled="disabled">已还款</a>
					</if>
					</span>
				</div>
			</dd>
			</foreach>
			<empty name="list"><dd style="text-align:center">检索结果为空</dd></empty>
		</dl>

		<div class="ui-pagination">{$page}</div>
	</div>
</div>
