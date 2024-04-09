<?php 

require_once('./models/Publisher.php');
require_once('./models/BookPublisher.php');
require_once('./models/Book.php');

class PublisherController extends Controller {
    public function index() {
        return view('publisher/index', 
        ['publishers' => Publisher::all(),
        'title' => 'Página principal de Editoriales' ]
        );
    }
    public function show($id){
        $publisher = Publisher::find($id);
        $books = Book::where('publisher_id', $id)->get();
        // $books = [];
        // for($i = 0; $i < sizeof($relBook); $i++){
        //     $book = Book::find($relBook[$i]['book_id']);
        //     $books[$i]['title'] = $book[0]['title'];
        //     $books[$i]['book_id'] = $book[0]['id'];
        // }
        return view('publisher/show',
          ['publisher'=>$publisher,
          'books' => $books,
          'title'=>'Datos de la Editorial',
          'show'=>true, 'create'=>false, 'edit'=>false]);
    }

    public function create(){
        $publisher = ['publisher'=>'', 'country'=>'', 'founded'=>'', 'genre'=>''];
        return view(
            'publisher/show',
            ['title'=>'Creación de Autor',
            'publisher'=>$publisher, 
            'show'=>false,'create'=>true,'edit'=>false]
        );
    }

    public function store($smth = null) {
        $publisher = Input::get('publisher');
        $country = Input::get('country');
        $founded = Input::get('founded');
        $genre = Input::get('genre');
        $publisher = ['publisher'=>$publisher,'country'=>$country,
                 'founded'=>$founded,'genre'=>$genre];
        DB::table('publisher')->insert($publisher);
        return redirect('/publisher');
      }

      public function edit($publisher_id){
        $publisher = DB::table('publisher')->find($publisher_id);
        return view(
            'publisher/show',
            ['publisher'=>$publisher,
            'title'=>'Edición de Autor',
            'show'=>false,'create'=>false,'edit'=>true]
        );
    }
    public function update($_,$pblisher_id=null){
        return;
    }

    public function destroy($publisher_id) {
        DB::table('publisher')->delete($publisher_id);
        return redirect('/publisher');
    }
}