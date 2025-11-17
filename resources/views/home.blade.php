@extends('layouts.app')

@push('navbar-top-left')
<li class="nav-item">
    <a href="#" class="nav-link">Extra link left</a>
</li>
@endpush

@push('navbar-top-right')
<li class="nav-item">
    <a href="#" class="nav-link active">Extra link right</a>
</li>
@endpush

@section('content')
<div class="container">


    <header class="pb-3 mb-4 border-bottom text-light">
        <a href="/" class="d-flex align-items-center text-light text-decoration-none"> <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="me-2" viewBox="0 0 118 94" role="img">
                <title>Bootstrap</title>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"></path>
            </svg> <span class="fs-4">Jumbotron example</span>
        </a>
    </header>

    <div class="p-5 mb-4 bg-body-secondary rounded-3 ">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Dashboard</h1>
            <p class="lead">{{ __('You are logged in!!') }}</p>
            <p class="col-md-8 fs-4 fst-italic">
                Using a series of utilities, you can create this jumbotron, just like the one
                in
                previous versions of Bootstrap. Check out the examples below for how you can remix and restyle it to
                your
                liking.
            </p>
            <button class="btn btn-primary btn-lg" type="button">Example button</button>
        </div>
    </div>

    <div class="my-5 p-5 bg-light">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Aliquam curae aptent maximus aptent habitasse augue aliquam eros ultricies mattis hendrerit inceptos etiam. Enim dictum lorem vitae rhoncus interdum pulvinar hac blandit euismod per imperdiet erat ullamcorper hendrerit.</p>
        <p>Enim mollis nibh suspendisse volutpat id dignissim congue at. Per justo eleifend proin vel urna nulla ligula enim elementum odio arcu litora habitant vehicula semper.</p>
        <p>Sollicitudin hendrerit tortor hendrerit feugiat sociosqu malesuada convallis arcu tempus et tempor pellentesque. Gravida aliquam viverra tristique feugiat potenti accumsan himenaeos euismod sollicitudin accumsan per elementum. Suspendisse volutpat mi iaculis maecenas dictumst felis enim magna platea gravida venenatis eu donec phasellus. Egestas tincidunt curae torquent iaculis per justo nibh ullamcorper aliquet. Habitant proin pulvinar in litora non erat magna.</p>
        <p>Nec risus sit accumsan bibendum id lobortis integer pellentesque maecenas. Habitant malesuada himenaeos aptent quis in feugiat arcu metus quam mollis interdum vestibulum torquent. Porta donec potenti sociosqu litora molestie sagittis adipiscing dapibus eget.</p>

        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Suspendisse suspendisse porta suspendisse praesent dictumst ullamcorper at sit sem cras quisque. Odio ornare curabitur condimentum ante imperdiet dui gravida ad cras vulputate. Gravida conubia praesent tellus mauris luctus viverra magna. Dictum in aptent felis auctor gravida viverra nisi amet condimentum litora.</p>
        <p>Velit hac phasellus proin sapien curabitur donec suspendisse dictumst. Lobortis nostra est luctus tellus auctor eu rhoncus id. Rhoncus vel nunc nunc fringilla duis ultricies donec eros. Tellus vulputate tellus quis semper odio nisl.</p>
        <p>Venenatis ullamcorper elit accumsan habitasse curabitur urna sociosqu habitant curae cursus sem tristique urna ultrices vestibulum. Porta senectus elit ex magna porttitor odio magna maximus. Sit condimentum ligula faucibus ac dignissim posuere torquent purus curabitur urna volutpat felis tempor adipiscing.</p>
        <p>Tellus et sapien tortor sodales feugiat gravida interdum faucibus eros. Gravida hendrerit senectus vitae nibh nunc conubia euismod porttitor. Integer hac sed enim lacus himenaeos consectetur posuere curae urna.</p>
        <p>Ipsum dignissim purus dapibus viverra aliquam tellus inceptos. Cursus molestie at praesent felis sociosqu taciti mattis dolor et orci adipiscing molestie consequat vestibulum bibendum. Duis auctor aliquet a varius sit vestibulum. Sit platea metus condimentum iaculis torquent tempus sociosqu duis interdum nullam.</p>
        <p>Curabitur vulputate est sed sit felis sollicitudin varius vestibulum placerat praesent viverra. Congue litora tempus nam phasellus non suscipit class facilisis felis lectus eget. Dictum nam lacus vehicula etiam gravida arcu ornare tortor. Scelerisque leo mauris velit ipsum faucibus scelerisque.</p>
        <p>Est hac diam velit faucibus nam placerat non ornare consequat malesuada. Et est hendrerit blandit eu rutrum magna dictumst elementum platea. Sociosqu nullam cursus erat in ultrices nec dictumst ut tincidunt urna nibh fringilla. Eget molestie hac aliquet sed iaculis.</p>
        <p>Diam odio maecenas taciti sed sem. Hac porta platea sed accumsan laoreet est malesuada ultricies. Commodo velit platea rutrum litora arcu suscipit cras rhoncus. Dictumst nunc sapien tempus ultricies fermentum quisque sociosqu platea. Consectetur torquent pharetra laoreet elementum duis elementum nulla volutpat nam massa vulputate ac scelerisque nulla.</p>
    </div>

</div>
@endsection