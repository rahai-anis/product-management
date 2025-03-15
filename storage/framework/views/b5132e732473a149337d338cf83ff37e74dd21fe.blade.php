<?php extract(collect($attributes->getAttributes())->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['darkMode','tag','wire:click','xOn:click','href','target','disabled','color','icon','size','class','outlined','iconPosition','iconPosition'])
<x-notifications::button :dark-mode="$darkMode" :tag="$tag" :wire:click="$wireClick" :x-on:click="$xOnClick" :href="$href" :target="$target" :disabled="$disabled" :color="$color" :icon="$icon" :size="$size" :class="$class" :outlined="$outlined" :iconPosition="$iconPosition" :icon-position="$iconPosition" >

{{ $slot ?? "" }}
</x-notifications::button>