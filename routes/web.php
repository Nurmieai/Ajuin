<?php

use App\Livewire\CreatePost;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', CreatePost::class);