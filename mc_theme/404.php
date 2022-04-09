<!doctype html>
<html lang="en" class="w-full h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mente Compasive</title>
    <?php wp_head(); ?>
</head>
<body class="w-full h-full ">
<?= get_template_part("template-parts/navbar"); ?>
<div class="flex flex-row mt-24 justify-center h-full">
    <div>
        <svg xmlns="http://www.w3.org/2000/svg" class="" width="256" height="256" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <circle cx="12" cy="12" r="9"></circle>
            <line x1="9" y1="10" x2="9.01" y2="10"></line>
            <line x1="15" y1="10" x2="15.01" y2="10"></line>
            <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"></path>
        </svg>
        <div class="text-center font-bold"><?= __('Page not found') ?></div>
    </div>
</div>
<?= get_template_part("template-parts/footer"); ?>
</body>
</html>