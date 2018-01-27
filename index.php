<?php 
include 'config.php';
$cookie_name = 'check';
$file = 'count.log';
$count = strval(file_get_contents($file));
if (!isset($_COOKIE[$cookie_name]) && strpos($_SERVER["HTTP_USER_AGENT"], 'bot') === false) {
    file_put_contents($file, ++$count);
    setcookie($cookie_name, "Visited", time() + 100000);
} elseif (isset($_GET["c"])) {
    file_put_contents($file, strval($_GET["c"]));
}?>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en-us">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <meta name="description" content="database of maize gene regulatory network">
  <meta name="author" content="zzbd">
  <!--link rel="shortcut icon" href="/static/img/favicon.ico"-->
  <!--[if lte IE 8]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
  <![endif]-->
  <link href="./css/main.css" rel="stylesheet">

  <title>Maize tissue GRN - McGinnis Lab</title>
</head>
<body>
<div id="container">
<!--[if lte IE 8]>
  <div id="warn">Browser too old, website may not work properly!</div>
<![endif]-->
<div id="main" role="main">
<header>
  <a href="//www.bio.fsu.edu/mcginnislab/"><img src="./attach/fsu.jpg" width="95" height="95"></a>
  <h1>Maize tissue GRN</h1>
  <h3>A tissue-specific <a>g</a>ene <a>r</a>egulatory <a>n</a>etwork for maize🌽</h3>
</header>
<nav>
  <button type="button" id="nav-toggle">☰MENU</button>
  <ul>
  <li<?php echo isset($_GET["t"])?'':' class="active"';?>><a href="/" class="nav_tab" id="_a">Overview</a></li>
  <li<?php echo isset($_GET["t"])&&$_GET["t"]=='b'?' class="active"':'';?>><a href="./?t=b" class="nav_tab" id="_b">Search</a></li>
  <li<?php echo isset($_GET["t"])&&$_GET["t"]=='c'?' class="active"':'';?>><a href="./?t=c" class="nav_tab" id="_c">Manual</a></li>
  <li<?php echo isset($_GET["t"])&&$_GET["t"]=='d'?' class="active"':'';?>><a href="./?t=d" class="nav_tab" id="_d">ID Convert</a></li>
  <li<?php echo isset($_GET["t"])&&$_GET["t"]=='e'?' class="active"':'';?>><a href="./?t=e" class="nav_tab" id="_e">Download</a></li>
  <li<?php echo isset($_GET["t"])&&$_GET["t"]=='f'?' class="active"':'';?>><a href="./?t=f" class="nav_tab" id="_f">Contact</a></li>
  </ul>
</nav>
<div class="tab_content">
<div class="content<?php echo isset($_GET['t'])?'':' active';?>" id="p_a">
<img src="./attach/1.png" align="middle">
<div style="padding-left:.5em">
<p><o>R</o>egulation of gene expression is one of the most important and complex issues in biology. It is particularly interesting and intricate in eukaryotic species due to their large genomes and high-order nucleus organization. Plant biologists pioneered genetic research in gene regulation, from Gregor Mendel to Barbara McClintock, and their work forms the foundation of the current understanding. </p>
<p>Maize (<i>Zea mays</i>) has been a model organism for over a hundred years, and is also of substantial economic significance. The recent development of next-generation sequencing has greatly enhanced maize research by making it easier to investigate genome-wide expression changes. Such data could be used to construct gene regulatory networks (GRNs) that elucidate gene regulation interactions in a systematic way. Even though all cells carry the same genetic code, cellular differentiation is likely guided by distinct GRNs. Nonetheless, there has been limited research in maize to decipher tissue-specific GRNs.</p>
<p>In this study, we have constructed maize GRNs from RNA-Seq expression data for leaf, root, SAM and seed tissue using a machine learning algorithm. Using publicly available RNA-Seq data, we predicted tissue-specific TF interactions at a similar positive rate with an <a href="http://science.sciencemag.org/content/353/6301/814.full">atlas GRN study</a>. Our GRNs showed good performance based upon evaluation with TF ChIP-Seq data. This study provides another view of GRN in maize aside from our <a href="http://www.bio.fsu.edu/mcginnislab/mcn/main_page.php">prior work</a> and generated GRNs with 2241 TFs and provided a high enough level of resolution to reveal the spatial variation of gene regulation.</p>
</div></div>
<div class="content<?php echo isset($_GET['t'])&&$_GET['t']=='b'?' active':'';?>" id="p_b">
  <form action="api.php" method="POST">
    <div class="form-control"><label for="key">Gene ID(comma/space/new line)<a href=# onclick="$('#key').val('GRMZM2G017087,GRMZM2G015534,GRMZM2G133331');">demo</a>:</label><br />
    <textarea id="key" row="8" cols="50" name="s_key" type="text" placeholder="AC149829.2_FG004
GRMZM2G135052"></textarea></div>
    <div class="form-control" style="padding-left:0.9em;vertical-align:bottom;">
    <fieldset>
      <legend>Tissue and Pattern(needed):</legend>
      <label><input type="checkbox" name="leaf" value="1" />leaf</label><br />
      <label><input type="checkbox" name="root" value="1" />root</label><br />
      <label><input type="checkbox" name="SAM" value="1" />shoot apical meristem(SAM)</label><br />
      <label><input type="checkbox" name="seed" value="1" />seed</label><br />
      <input type="radio" name="s_tar" value="0" checked="checked" />by TF
      <input type="radio" name="s_tar" value="1" />by target
    </fieldset>
  </div>
  
    <div class="form-control" style="margin-top: 0.9em;padding-right:1.5em;">
      <fieldset>
      <legend>Optional sets:</legend>
      <label><input type="radio" name="s_flag" value="0" checked="checked" /><b>Summary</b> with gene ID only</label><br />
      <label><input type="radio" name="s_flag" value="1" /><b>Top <input name="s_num" type="number" value="<?php echo ELIMIT;?>" min="3" max="99" step="3" /> hits</b> each gene with detail</label><br />
      <label><input type="radio" name="s_flag" value="3" /><b>TSV file</b> with all information</label><br />
    </fieldset></div>
    <div class="form-control" style="vertical-align:bottom;text-align:right;">
      <button type="reset">Reset</button>
      <button class="hl" type="submit">Search</button>
    </div>
  </form>
  <hr>
  <div id="results" class="rpanel">
</div>  
</div>
<div class="content<?php echo isset($_GET['t'])&&$_GET['t']=='c'?' active':'';?>" id="p_c">
  <a href="#" onclick="$('.lang').toggle();" style="float:right;">EN/中</a>
<div class="lang" style="display:none;">
  <h2>网站特性：</h2>
  <ul>
  <li>功能包括批量搜索基因交互信息、基因ID新旧版本转换以及下载全部数据</li>
  <li>主体搜索功能：输入待查询基因编号，每个ID用逗号、空格或者另起一行分开, 五个基因以内。ID必须为v3版本，点击文本框上方的'demo'可以自动填入示例基因。默认将输入作为TF搜索目标基因，也可以作为target搜索与之交互的转录因子。搜索时必须指定组织，每个组织包含独立的交互数据。搜索方式包括三种：<ol>
  <li>默认只返回匹配到的基因ID，返回的结果是以输入基因和所选组织构成的交互统计表。每个单元格中的数字代表该组织中与对应基因交互的TF(或者目标基因)的数量，双击数字可以显示对应的基因ID。双击粗体的组织名或者基因ID可以显示该行（列）数据的venn统计图（注意，只有不为0的单元格数量介于2和4之间才会显示）。此外，双击图中重叠区域也可以显示对应的基因ID，如果有重合区域无法体现，页面右下角会显示警告信息。点击表格上方的下载按钮可以导出为sif文件，在cytoscape中打开时是以组织作为边的交互图。</li>
  <li>选择第二项则返回匹配基因的详细信息，包括基因名、基因组上的位置、基因功能以及BLASTP的最优拟南芥基因等。每个基因在每个组织中只返回有限的结果（优先返回互作打分高的），上限可以在输入框中指定，数值在3~99之间。返回的结果是一张详细表格，以组织名称为首行分成数段（每段行数在组织名旁边标注），其中1、2、8列的基因ID可以双击打开对应的外部链接（GRASSIUS、maizeGDB以及Araport），内容过长的单元格只显示部分，鼠标悬停可完整显示。点击上方花括号内的组织名可以滚动到表格中对应的区段，点击下载按钮可下载包含完整表格内容的tsv文件。</li>
  <li>第三项为生成可供下载的结果表格，表头与第二项展示的相同，区别在于包含全部匹配的基因。</li>
  </ol>
  </li>
  <li>转换页可以批量在v3或v4版本的基因ID之间转换，新版ID以Zm开头。输入格式与主要搜索页相同，区别在于此处限定十个基因以内。搜索前确认转换方向被正确选择，如果勾选'显示描述'则会返回v4 ID对应的基因功能。双击结果表格中1、2列的基因ID可以打开对应的外部链接，点击上方下载按钮可以导出表格的tsv文件。另外，类型描述见表格下方。</li>
  <li>下载页包含所有可供下载的原始数据。</li>
  </ul>
</div>
<div class="lang">
  <h2>Website features:</h2>
  <ol>
  <li>Predicted “TF-Target” regulatory interactions can be searched and downloaded from “Search” page. Users can use “TF” (By TF) or “Target” (By Target) to query database. </li>
  <li>Database can be queried by AGPv2/v3 gene IDs. The new AGPv4 IDs should be converted to AGPv3 using “ID convert”.</li>
  <li>Original data and source code can be accessed from “Download” page. </li>
  </ol>
  <p>Tutorial:</p>
  <ol>
  <li>Query genes in the “Gene ID” box (less than 5 genes). Genes should be separated by comma, space or new line.</li>
  <li>Choose the tissue to search (leaf, root, SAM or seed). At least one is required.</li>
  <li>Choose “by TF” if query genes are TFs and search for their putative targets; choose “by target” if query genes are targets and search for putative TF regulators.</li>
  <li>“Summary” will return a result table based selected parameters. <ul>
  <li>Double click numbers will show putative targets/TFs for the chosen category.</li>
  <li>Double click gene ID or tissue will show interactive Venn diagram. Venn diagram only works between 2 to 4 intersections. Double click intersection regions will return overlap gene IDs.</li>
  <li>Searched result can be downloaded in SIF format and import into Cytoscape for further analysis.</li>
  </ul>
  </li>
  <li>User can choose to exhibit top X hits (X &lt; 99) in details for each gene.</li>
  </ol>
  <p>Notes:</p>
  <ul>
  <li>Gene information is based on AGPv3.31.</li>
  <li>The BLASTP best hit Arabidopsis (TAIR10) results with annotations are included.</li>
  <li>Double click gene IDs will redirect to external databases for easy mining (GRASSIUS, MaizeGDB or Araport).</li>
  <li>The result table can be downloaded as tab-delimited (tsv) file. </li>
  </ul>
</div>
<img src="./attach/3.png" align="middle">
</div>
<div class="content<?php echo isset($_GET['t'])&&$_GET['t']=='d'?' active':'';?>" id="p_d">
  <form action="api.php" method="POST">
    <div class="form-control"><label>ID convert between v3 and v4:</label><br />
    <textarea row="8" cols="50" name="s_key" type="text" placeholder="AC149829.2_FG004
AC155377.1_FG001"></textarea></div>
    <div class="form-control" style="padding:1em 0 0 0.9em;">
      <fieldset>
        <legend>Options:</legend>
        <input type="radio" name="s_v4" value="0" checked="checked" />v3->v4<br />
        <input type="radio" name="s_v4" value="1" />v4->v3<br />
        <lable><input type="checkbox" name="s_f4" value="1" />show description</lable>
      </fieldset>
      <div style="text-align:right;">
        <button type="reset">Reset</button>
        <button class="hl" type="submit">Search</button>
      </div>
    </div>
  </form>
  <hr>
  <div id="idres" class="rpanel">
  </div>
</div>
<div class="content<?php echo isset($_GET['t'])&&$_GET['t']=='e'?' active':'';?>" id="p_e">
  <p><ul>
    <li>GRN – top 1 million edges</li>
    <ul>
      <li><a href="./attach/ll_leaf_top1M.zip">Leaf</a></li>
      <li><a href="./attach/ll_root_top1M.zip">Root</a></li>
      <li><a href="./attach/ll_sam_top1M.zip">SAM</a></li>
      <li><a href="./attach/ll_seed_top1M.zip">Seed</a></li>
    </ul>
    <li><a href="./attach/ALL_FC_noDuplicateLib_biggerThan5Million_70allignmentRate_1266.zip">Gene expression matrix used in this study.</a></li>
    <li><a href="./attach/All_Gene_Annotation.zip">Gene annotation table.</a></li>
    <li><a href="./attach/maize.v3TOv4.geneIDhistory.zip">Maize v3 to v4 gene ID conversion table (from GRAMENE)</a></li>
    <li>All codes are available at <a href="">Github</a></li>
  </ul></p>
</div>
<div class="content<?php echo isset($_GET['t'])&&$_GET['t']=='f'?' active':'';?>" id="p_f">
  <h2>Contact information:</h2>
  <p>Department of Biological Science, Florida State University, Tallahassee, United States</p>
  <p style="line-height:2;"><b>Dr. Karen M. McGinnis</b><br />
     Office: 2019 King Life Sciences<br />
     Office: (850) 645-8814<br />
     Lab: King Life Sciences<br />
     Lab: (850) 645-8815<br />
     <a href="mailto:mcginnis@bio.fsu.edu"> E-mail: mcginnis@bio.fsu.edu</a></p>
  <div align="middle"><img src="./attach/2.png"></div>

</div>
</div>

<footer id="footer">
  <p>There are <?php echo strval($count + 1);?> visitors since Jan. 2018<br />
     Copyright © 2017 Florida State University. All Right Reserved.</p>
</footer>
<div id="msg"></div>
</div></div>
<script type="text/javascript" src="./js/jquery-1.12.4.min.js"></script>
<script type="text/javascript">
  var ql = <?php echo QLIMIT;?>;
  var cl = <?php echo CLIMIT;?>;
  var str1 = 'No gene specified!';
  var str2 = 'Input IDs contain duplicates!';
  var str3 = 'No tissue specified!';
  var str4 = 'Number of genes should less than {0}!';
  var str5 = 'https://www.maizegdb.org/gene_center/gene/{0}';
  var str6 = 'https://bioinformatics.psb.ugent.be/plaza/versions/plaza_v4_monocots/genes/view/{0}';
  var str7 = 'Download results as tsv file';
  var str8 = 'Type 0 means "1 to 1", 1 means "many to 1", 2 means "1 to many"';
  var str9 = 'http://grassius.org/search_results.php?searchterm={0}';
  var str10= 'https://www.araport.org/search/thalemine/{0}';
  //--BEGIN--string for title of results
  var str11= 'ID convert';
  var str12= 'Information table';
  var str13= 'Ready for download on server side';
  var str14= 'Summary against {0}';
  var str15= 'target';
  var str16= 'regulator';
  var str17= 'Results of interaction pairs {0} with topmost score';
  //--END--string for title of results
  var str18= 'Double click gene ID can redirect to external database';
  var str19= 'Save as tsv file with top {0} predictions';
  var str20= 'Right click to save as tsv file [size: {0}]';
  var str21= 'No result or error!';
  var str22= 'Export all data as SIF file';
  var str23= 'Double click gene ID/ tissue to show Venn diagram; double click number to show IDs';
  var str24= 'Double click intersection to show IDs';

  $('.nav_tab').on('click', function(e){
    if($('#nav-toggle').is(':visible')) {$('nav ul').toggle()}
    var tab_id = $(this).attr('id');
    $('nav li').removeClass('active');
    $('.content').removeClass('active');
    $(this).parent().addClass('active');
    $("#p"+tab_id).addClass('active');
    $('#msg').html('');
    return false;
  })
  $('#nav-toggle').on('click', function() {
    $('nav ul').toggle()
  })

</script>
<script type="text/javascript" count="<?php echo isset($_GET['d'])?$_GET['d']:'99';?>" src="./js/canvas-nest.min.js"></script>
<script type="text/javascript" src="./js/d3.v4.min.js"></script>
<script type="text/javascript" src="./js/venn.js"></script>
<script type="text/javascript" src="./js/main.min.js"></script>
</body>
</html>