<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">


    <!-- important pour rendre la page responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <meta name="description" content="XoomCoder - Formation Développeur Fullstack à Distance">
    <title>XoomCoder * Formation Développeur Fullstack à Distance</title>

    <link rel="canonical" href="https://xoomcoder.com/">
    <!-- favicon -->
    <link rel="icon" href="assets/img/xoomcoder.svg">


    <style>
html, body {
    padding:0;
    margin:0;
    font-size:16px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
body {
    display: flex;
    flex-direction: column;
    align-items: center;
}
* {
    width:100%;
    box-sizing: border-box;
    text-align:center;
    transition: all 0.4s ease-in;
}

header {
    padding: 1rem;
    display: grid;
    align-items: center;
    justify-content: left;
}
footer {
    padding: 4rem 0 8rem;
}
main {
    display:flex;
    flex-direction: column;
    align-items:center;
    border-bottom: 2rem solid #000000;
}
header, main, footer {
    max-width: 1366px;
}
p {
    padding:0 1rem;
}  

pre {
    margin:0;
    padding: 0 1rem;
    display: block;
    white-space: pre-wrap;
    text-align: left;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
section {
    padding:2rem 0 0 0;
}

h1 {
    font-size:1.5rem;
    padding:0.5rem;
    margin:0;
    vertical-align: middle;
}
h2 {
    font-size:1.2rem;
    padding:2rem;
    margin:0;
}
h3 {
    font-size:1rem;
    padding:0.5rem;
    margin:0;
}

img {
    object-fit: cover;
}
article {
    padding:0.5rem;
}

article img {
    height:10vmin;
    object-fit:contain;
}
.logo {
    width:5vmin;
    height:5vmin;
    object-fit: contain ;
}

/* https://developer.mozilla.org/fr/docs/Web/CSS/Requ%C3%AAtes_m%C3%A9dia/Utiliser_les_Media_queries */
@media (min-width: 480px)
{

    section {
        display:flex;
        flex-wrap:wrap;
        justify-content: center;
        align-items:stretch;
    }
    section.x2col > * {
        width: calc(100% / 2);
    }
    section.x2col h2 {
        width:100%;
    }
    section article {
        width: calc(100% / 2);
    }

    .w10 {
        width:10%;
    }
    .w20 {
        width:20%;
    }
    .w30 {
        width:30%;
    }
    .w40 {
        width:40%;
    }
    .w50 {
        width:50%;
    }

}

@media (min-width: 960px)
{

    section article {
        width: calc(100% / 4);
    }

}

.h10{
    height: 10vmin;
} 
.h20{
    height: 20vmin;
} 
.h30{
    height: 30vmin;
} 
.h40{
    height: 40vmin;
} 
.h50{
    height: 50vmin;
}
.h60{
    height: 60vmin;
}
.h70{
    height: 70vmin;
}
.h80{
    height: 80vmin;
}
.h90{
    height: 60vmin;
}
.h100{
    height: 100vmin;
}

.of-in {
    object-fit: contain;
}
.of-out {
    object-fit: cover;
}

/*********************/
/* COLORS            */
/*********************/


body {
    background: #000000 url(https://xoomcoder.com/assets/img/code-640.jpg) fixed repeat center center;
    background-size: cover;
}  
header {
    background: url('assets/img/code-1600.jpg') right top;
    background-size: cover;
}
main {
    background: none;
}
section {
    background-color: #fcfcfc;
}
h2, h3 {
    color: #212121;;
}
header, header h1 a, footer {
    color:#ffffff;
    text-shadow: -1px -1px 0 #000, 1px -1px 0 #000, -1px 1px 0 #000, 1px 1px 0 #000;
}
footer {
    background-color: rgba(0,0,0,0.5);
}

.s1, .s3, .s5, .s7 {
    color: #000000;
}
.s2, .s6, .s8 {
    background-color: #eeeeee;
}
article {
    border-top:1px solid #ba000d;
}
a {
    text-decoration: none;
    color:#ba000d;
}
.nobg {
    background:none;
}
.nobg * {
    color: white;
}

    </style>
</head>
<body>
    <header>
        <h1 class="">
            <a href="./">XoomCoder</a>
        </h1>
        <a href="./">
            <img class="logo" src="assets/img/xoomcoder.svg" alt="xoomcoder.com">
        </a>
        <strong>Formation Développeur Fullstack à&nbsp;Distance</strong>
    </header>
    <main>
