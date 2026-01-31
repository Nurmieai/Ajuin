<?php

use App\Livewire\CreatePost;
use App\Livewire\Login;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', CreatePost::class);
Volt::route('/login', Login::class);