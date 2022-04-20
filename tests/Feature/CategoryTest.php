<?php

use App\Models\Category;

test('guest can visit category show page', function () {
    $category = Category::find(rand(1, 3));

    $this->get(route('categories.show', ['category' => $category->id, 'name' => $category->name]))
        ->assertStatus(200);
});

test('visit category show page and url don\'t include slug', function () {
    $category = Category::find(rand(1, 3));

    $this->get(route('categories.show', ['category' => $category->id]))
        ->assertStatus(301)
        ->assertRedirect(route('categories.show', [
            'category' => $category->id,
            'name' => $category->name
        ]));
});
