  <!--container-->
  <div class="ui-container">
    <include file="Cms:left" />
    <div class="ui-grid9 ui-right">
      <div class="ui-panel">
        <h1 class="ui-text-brown">{$cate['name']}</h1>
		<hr/>
        <ul class="ui-list">
          <foreach name="list" item="vo">
          <eq name="vo['islink']" value="1">
          <li class="ui-clearfix">
            <p><a href="{$vo['linkurl']}" title="{$vo['title']}" target="_blank">{$vo['title']}</a></p>
            <span class="ui-text-gray">{$vo['timeadd']|substr=0,16}</span>
          </li>
          <else/>
          <li class="ui-clearfix">
            <p><a href="/d-{$vo['code']}.html" title="{$vo['title']}" target="_blank">{$vo['title']}</a></p>
            <span class="ui-text-gray">{$vo['timeadd']|substr=0,16}</span>
          </li>
          </eq>
        </foreach>
        </ul>
		{$page}
      </div>
    </div>
  </div>