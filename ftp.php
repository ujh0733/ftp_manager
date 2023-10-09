<?php
    include "./include/head.html";
    include "./ftp/ftp_controller.php";
    
    if($_SESSION["ftp_host"] && $_SESSION["ftp_user"] && $_SESSION["ftp_pass"]){
        $ftp_host = $_SESSION["ftp_host"];
        $ftp_user = $_SESSION["ftp_user"];
        $ftp_pass = $_SESSION["ftp_pass"];

        $ftp_controller = new Ftp_Controller();
        $file_list = $ftp_controller->get_file_list();

        $dir_json = json_encode($file_list);
    }else{
        $tools->alert_location("로그인을 진행헤 주세요", "./index.htm");
    }
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />

<script>
    $(function (){
        create_tree();
    });
    
    function create_tree(){
        $('#tree').jstree({ 
            'plugins' : ["types"],
            'types': {
                '#':{
                    "icon": ""
                },
                'dir':{
                    "icon": "fa-solid fa-folder"
                },
                'file':{
                    "icon": "fa-regular fa-file"
                }
            },
            'core' : {
                'data' : <?= $dir_json ?>,
            },
        })

       
    }
</script>
<body class="container mt-5">
    <div style="border: 1px solid red; width:650px; height:650px;">
        <div id="tree">

        </div>
    </div>
</body>

<?php include "./include/footer.html"; ?>