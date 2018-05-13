@foreach ($categories as $category_list)
  <li>
    <a href="{{$category_list->link()}}">
      {!! $delimiter or "" !!}{{$category_list->title or ""}}
    </a>
  </li>
  @if (count($category_list->children) > 0)

    @include('categories.partials.asideNav', [
      'categories' => $category_list->children,
      'delimiter'  => ' - ' . $delimiter
    ])

  @endif
@endforeach