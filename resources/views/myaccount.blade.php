<style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }

            .myaccount {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 96px;
            }
        </style>
@extends('layouts.header')
  
<body>
    
<div class="myaccount">
    <div class="content">
        <div class="title">Welcome {{{ $title}}}</div>
    </div>
</div>
</body>
    