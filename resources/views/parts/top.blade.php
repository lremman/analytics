<div class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <a href="/" class="navbar-brand">Rdev Analytics</a>
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="navbar-collapse collapse" id="navbar-main">
      <ul class="nav navbar-nav">

        
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li>
          <a href="{{ route('likes') }}">Мені подобається</a>
        </li>
        <li>
          <a href="{{ route('audio') }}">Жанри аудіо</a>
        </li>
        <li><a href="{{route('logout')}}">Вийти</a></li>
      </ul>

    </div>
  </div>
</div>