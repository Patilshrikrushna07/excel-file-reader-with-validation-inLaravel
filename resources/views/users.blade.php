<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Users</title>
  </head>
  <body>  
    <!-- <h1>Users</h1> -->

    <div class="container">
        <div class="row">
          <div class="col-md-12">
            @if(Session::has('success'))
            <div class="alert alert-light" role="alert">
            {{Session::get('success')}}
</div>
            @endif
          </div>
            <div class="col-md-12">
            <form class="row g-3" action="{{route('import_user')}}" method="post" enctype="multipart/form-data">
                @csrf
        <div class="col-auto">
            <label  class="visually-hidden">Excel</label>
            <input type="file" class="form-control" name="excel_file">
        </div>
      
        <button type="submit" class="btn btn-primary ">Upload Excel File</button>
        {{-- @error('excel_file')
            <span class="text-danger">{{$message}}</span>
      
            
        @enderror --}}
        <a class="btn btn-warning"
                       href="{{ route('export-users') }}">
                              Export User Data
                      </a>
    </form>
    {{-- @if(Session::has('import_error'))
      @foreach(Session::get('import_error') as $failure)
      <div class="alert alert-danger" role="alert">
        {{$failure->errors()[0]}} at line no: {{$failure->row()}}
</div>
      @endforeach
      @endif --}}


    @if (@isset($errors)&& $errors->any())
    @foreach($errors->all() as $error)
    <div class="alert alert-danger" role="alert"> 
    {{$error}}
    
    </div>   
    @endforeach
    @endif

    @if(session()->has('failures'))
      <table class="table table-danger">
          <tr>
              <th>Row</th>
              <th>Attrinute</th>
              <th>Errors</th>
              <th>Values</th>
            </tr>

      @foreach(session()->get('failures') as $validation)
      <tr>
        <td>{{$validation->row()}}</td>
        <td>{{$validation->attribute()}}</td>
        <td>
          <ul>
            @foreach($validation->errors() as $e)
            <li>{{$e}}</li>
            @endforeach
          </ul>
        </td>
        <td>
              {{$validation->values()[$validation->attribute()]}}
            </td>
      </tr>
      @endforeach
      </table>
    @endif
    </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h1>Doctors List</>
            </div>
            <div class="col-md-12">
            <table class="table">
  <thead>
    <tr>
      <th scope="col">ID</th>
      <th scope="col">Name</th>
      <th scope="col">Email</th>
      <th scope="col">Phone Number</th>
      <th scope="col">City</th>
      <th scope="col">State</th>
      <th scope="col">County Code</th>
      <th scope="col">Username</th>
    </tr>
  </thead>
  <tbody>
    @if(count($users))
    @foreach($users as $user)
    <tr>
      <th scope="row">{{$user->id}}</th>
      <td>{{$user->name}}</td>
      <td>{{$user->email}}</td>
      <td>{{$user->phone_number}}</td>
      <td>{{$user->city}}</td>
      <td>{{$user->state}}</td>
      <td>{{$user->country_code}}</td>
      <td>{{$user->username}}</td>
      {{-- <td>{{$user->email}}</td> --}}
    </tr>
    @endforeach
@else
<tr>
      <td colpan="3">No Data Found</td>
    </tr>

  </tbody>
  @endif
</table>
            </div>
        </div>
    </div>

    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>