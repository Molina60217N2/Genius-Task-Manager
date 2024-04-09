<?php 

class Author extends Model {
    protected static $table = "author";
    // static $authors = [
    //     [
    //         'id' => 1,
    //         'author' => 'Abraham Silberschatz',
    //         'nationality' => 'Israelis / American',
    //         'birth_year' => 1952,
    //         'fields' => 'Database Systems, Operating Systems',
    //         'books' => [
    //             ['book_id' => 1, 'title' => 'Operating System Concepts'],
    //             ['book_id' => 2, 'title' => 'Database System Concepts'],
    //         ],
    //     ],
    //     [
    //         'id' => 2,
    //         'author' => 'Andrew S. Tanenbaum',
    //         'nationality' => 'Dutch / American',
    //         'birth_year' => 1944,
    //         'fields' => 'Distributed computing, Operating Systems',
    //         'books' => [
    //             ['book_id' => 3, 'title' => 'Computer Networks'],
    //             ['book_id' => 4, 'title' => 'Modern Operating Systems'],
    //         ],
    //     ],
    // ];
    
    // public static function all() {
    //     return self::$authors;
    // }

    // public static function find($id) {
    //     //return self::$authors[$id];
    //     foreach (self::$authors as $key => $author)
    //           if ($author['id'] == $id) return $author;
    //         return [];
    // }
}