<!DOCTYPE html>
<html lang="fr" prefix="og: https://ogp.me/ns#">
<head>
    <meta charset="UTF-8">

    <!-- important pour rendre la page responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php Response::Htmlheader() ?>
    <!-- favicon -->
    <link rel="icon" href="assets/img/xoomcoder.svg">
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/site.css">
    <style>

    </style>
</head>
<body class="<?php Xoom::showBodyClass() ?>">
    <header>
        <nav>
            <h1 class="">
                <a href="./">XoomCoder</a>
            </h1>
            <a class="logo" href="./">
                <img src="assets/img/xoomcoder.svg" alt="xoomcoder.com">
            </a>
            <strong>Formation Développeur Fullstack à&nbsp;Distance</strong>
            <a class="menu news" href="news">news</a>
            <a class="menu tutoriels" href="tutoriels">tutoriels</a>
            <a class="menu formation" href="formation">formation</a>
            <a class="menu emploi" href="emploi">offres d'emploi</a>
            <a class="menu contact" href="contact">contact</a>
        </nav>
    </header>
    <main>
