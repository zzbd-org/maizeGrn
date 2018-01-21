<?php include 'config.php';?>
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
  <link href="/css/main.css" rel="stylesheet">

  <script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
  <title>Maize tissue GRN - McGinnis Lab</title>
</head>
<body>
<div id="container">
<div id="main" role="main">
<header>
  <a href="http://www.bio.fsu.edu/mcginnislab/"><img src="/attach/fsu.jpg" width="80" height="80"></a>
  <h1>Maize tissue GRN</h1>
  <h3>A tissue-specific <a>g</a>ene <a>r</a>egulatory <a>n</a>etwork for maize🌽</h3>
</header>
<nav>
  <button type="button" id="nav-toggle">☰MENU</button>
  <ul>
  <li class="active"><a href="#" class="nav_tab" id="_a">Overview</a></li>
  <li><a href="#" class="nav_tab" id="_b">Search</a></li>
  <li><a href="#" class="nav_tab" id="_c">Manual</a></li>
  <li><a href="#" class="nav_tab" id="_d">ID Convert</a></li>
  <li><a href="#" class="nav_tab" id="_e">Download</a></li>
  <li><a href="#" class="nav_tab" id="_f">Contact</a></li>
  </ul>
</nav>
<div class="content active" id="p_a">
<img src="/attach/1.png" align="middle">
<div style="padding-left:.5em">
<p>Regulation of gene expression is one of the most important and complex issues in biology. It is particularly interesting and intricate in eukaryotic species due to their large genomes and high-order nucleus organization. Plant biologists pioneered genetic research in gene regulation, from George Mendel to Barbara McClintock, and their work forms the foundation of the current understanding. </p>
<p>Maize (Zea Mays) has been a model organism for over a hundred years, and is also of substantial economic significance. The recent development of next-generation sequencing has greatly enhanced maize research by making it easier to investigate genome-wide expression changes. Such data could be used to construct gene regulatory networks (GRNs) that elucidate gene regulation interactions in a systematic way. Even though all cells carry the same genetic code, cellular differentiation is likely guided by distinct GRNs. Nonetheless, there has been limited research in maize to decipher tissue-specific GRNs.</p>
<p>In this study, we have constructed maize GRNs from RNA-Seq expression data for leaf, root, SAM and seed tissue using a machine learning algorithm. Using publicly available RNA-Seq data, we predicted tissue-specific TF interactions at a similar positive rate with an <a href="http://science.sciencemag.org/content/353/6301/814.full">atlas GRN study</a>. Our GRNs showed good performance based upon evaluation with TF ChIP-Seq data. This study provides another view of GRN in maize aside from our <a href="http://www.bio.fsu.edu/mcginnislab/mcn/main_page.php">prior work</a> and generated GRNs with 2241 TFs and provided a high enough level of resolution to reveal the spatial variation of gene regulation.</p>
</div></div>

<div class="content" id="p_b">
  <form method="POST">
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
      <input type="radio" name="s_flag" value="0" checked="checked" /><b>Summary</b> with gene ID only<br />
      <input type="radio" name="s_flag" value="1" /> <b>Top <input name="s_num" type="number" value="<?php echo ELIMIT;?>" min="3" max="99" step="3" style="width: 35px;" /> hits</b> each gene with detail<br />
      <input type="radio" name="s_flag" value="3" /><b>TSV file</b> with all information<br />
    </fieldset></div>
    <div class="form-control" style="vertical-align:bottom;text-align:right;">
      <button class="orange" type="reset">Reset</button>
      <button type="submit">Search</button>
    </div>
  </form>
  <hr>
  <div id="results" style="min-height:100px;">
</div>  
</div>
<div class="content" id="p_c">
  <p>网站特性：</p>
  <ol>
  <li>功能包括批量搜索基因交互信息、基因ID新旧版本转换以及下载全部数据</li>
  <li>主体搜索功能：输入待查询基因编号，每个ID用逗号、空格或者另起一行分开。ID必须为v3版本，点击文本框上方的’demo’可以自动填入示例基因。默认将输入作为TF搜索目标基因，也可以作为target搜索与之交互的转录因子。搜索时必须指定器官组织，每个组织包含独立的交互数据。搜索方式包括三种：
  <ol>
  <li>默认只返回匹配到的基因ID，返回的结果是以输入基因和所选组织构成的交互统计表。每个单元格中的数字代表该组织中与对应基因交互的TF(或者目标基因)的数量，双击数字可以显示对应的基因ID。双击粗体的组织名或者基因ID可以显示该行（列）数据的venn统计图（注意，只有不为0的单元格数量介于2和4之间才会显示）。此外，双击图中重叠区域也可以显示对应的基因ID，如果有重合区域无法体现，页面右下角会显示警告信息。点击表格上方的下载按钮可以导出为sif文件，在cytoscape中打开时是以组织作为边的交互图。</li>
  <li>选择第二项则返回匹配基因的详细信息，包括基因名、基因组上的位置、基因功能以及同源的拟南芥基因等。每个基因在每个组织中只返回有限的结果（优先返回互作打分高的），上限可以在输入框中指定，数值在3~99之间。返回的结果是一张详细表格，以组织名称为首行分成数段（每段行数在组织名旁边标注），其中1、2、8列的基因ID可以双击打开对应的外部链接（grassius、maizegdb以及araport），内容过长的单元格只显示部分，鼠标悬停可完整显示。点击上方花括号内的组织名可以滚动到表格中对应的区段，点击下载按钮可下载包含完整表格内容的tsv文件。</li>
  <li>第三项为生成可供下载的结果表格，表头与第二项展示的相同，区别在于包含全部匹配的基因。</li>
  </ol>
  </li>
  <li>ID转换页可以批量在v3或v4版本的基因ID之间转换，新版ID以Zm开头。输入格式与主要搜索页相同，区别在于此处不限制基因数量。搜索前确认转换方向被正确选择，如果勾选’显示描述’则会返回v4 ID对应的基因功能。双击结果表格中1、2列的基因ID可以打开对应的外部链接，点击上方下载按钮可以导出表格的tsv文件。另外，类型描述见表格下方。</li>
  <li>下载页包含所有可供下载的原始数据。</li>
  </ol>
</div>
<div class="content" id="p_d">
  <form method="POST">
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
        <button class="orange" type="reset">Reset</button>
        <button type="submit">Search</button>
      </div>
    </div>
  </form>
  <hr>
  <div id="idres" style="min-height:100px;">
  </div>
</div>
<div class="content" id="p_e">
  <p><ul>
    <li>GRN – top 1 million edges</li>
    <ul>
      <li><a href="/attach/ll_leaf_top1M.zip">Leaf</a></li>
      <li><a href="/attach/ll_root_top1M.zip">Root</a></li>
      <li><a href="/attach/ll_sam_top1M.zip">SAM</a></li>
      <li><a href="/attach/ll_seed_top1M.zip">Seed</a></li>
    </ul>
    <li><a href="/attach/ALL_FC_noDuplicateLib_biggerThan5Million_70allignmentRate_1266.zip">Gene expression matrix used in this study.</a></li>
    <li><a href="/attach/All_Gene_Annotation.zip">Gene annotation table.</a></li>
    <li><a href="/attach/maize.v3TOv4.geneIDhistory.zip">Maize v3 to v4 gene ID conversion table (from GRAMENE)</a></li>
    <li>All codes are available at Github (link later)</li>
  </ul></p>
</div>
<div class="content" id="p_f">
  <h2>Contact information:</h2>
  <p>Department of Biological Science, Florida State University, Tallahassee, United States</p>
  <p style="line-height:2;"><b>Dr. Karen M. McGinnis</b><br />
     Office: 2019 King Life Sciences<br />
     Office: (850) 645-8814<br />
     Lab: King Life Sciences<br />
     Lab: (850) 645-8815<br />
     <a href="mailto:mcginnis@bio.fsu.edu"> E-mail: mcginnis@bio.fsu.edu</a></p>
  <div align="middle"><img src="/attach/2.png"></div>

</div>
<footer id="footer" style="background-color:#333;text-align:center">
  <p style="color:#999;font-size:12px;">Copyright © 2017 Florida State University. All Right Reserved.</p>
</footer>
<div id="msg"></div>
</div></div>

<script type="text/javascript">
  var ql = <?php echo QLIMIT;?>
</script>
<script type="text/javascript" src="/js/canvas-nest.min.js"></script>
<script type="text/javascript" src="/js/d3.v4.min.js"></script>
<script type="text/javascript" src="/js/venn.js"></script>
<script type="text/javascript" src="/js/main.min.js"></script>
</body>
</html>