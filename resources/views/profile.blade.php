@extends('layouts.header')
<section id="profile-form"><!--form-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-1">
                        <div class="profile-form"><!--profile form-->
                            <h2>Edit your profile</h2>
                            <form method="POST" action="{{url('myaccount')}}">
                                {!! csrf_field() !!}
                                Email Address: <input type="email" name="email" id="email" value="{{Auth::user()->email}}" placeholder="Email Address" readonly/><br>
                                Name: <input type="text" name="name" id="name" placeholder="name" /><br>
                                Password: <input type="password" name="password" id="password" placeholder="Password" /><br>                                
                                <button type="submit" class="btn btn-default">Update</button>
                            </form>
                        </div><!--/profile form-->
                    </div>                   
                </div>
            </div>
        </section><!--/form-->