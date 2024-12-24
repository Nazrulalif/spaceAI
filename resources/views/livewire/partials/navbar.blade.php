<div>
    <!-- NavBar -->
    <nav class="navbar sticky-top navbar-expand-lg bg-dark">
        <div class="container-fluid">
    
          <button class="btn border-0" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseWidthExample" aria-expanded="true" aria-controls="collapseWidthExample"
            style="margin-right: 10px; padding: 0px 5px 0px 5px;" id="sidebartoggle" onclick="changeclass()"> <i
              class="bi bi-arrows-expand-vertical"></i>
          </button>
          <a class="navbar-brand font-monospace fs-5" href="/">Beta v1.0</a>
    
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="true" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end gap-3" id="navbarSupportedContent">
            {{-- <button class="btn btn-dark rounded-pill">Log in</button>
            <button class="btn rounded-pill">Sign up</button> --}}
    
            <!-- Cambair Tema -->
            <div class="dropdown-center" style="padding-left: 10px;">
              <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center " id="bd-theme" type="button"
                aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
                <svg class="bi my-1 theme-icon-active" width="1em" height="1em">
                  <use href="#circle-half"></use>
                </svg>
                <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
                <li>
                  <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light"
                    aria-pressed="false">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
                      <use href="#sun-fill"></use>
                    </svg>
                    Light
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                      <use href="#check2"></use>
                    </svg>
                  </button>
                </li>
                <li>
                  <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark"
                    aria-pressed="false">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
                      <use href="#moon-stars-fill"></use>
                    </svg>
                    Dark
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                      <use href="#check2"></use>
                    </svg>
                  </button>
                </li>
                <li>
                  <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto"
                    aria-pressed="true">
                    <svg class="bi me-2 opacity-50 theme-icon" width="1em" height="1em">
                      <use href="#circle-half"></use>
                    </svg>
                    Auto
                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                      <use href="#check2"></use>
                    </svg>
                  </button>
                </li>
              </ul>
            </div>
            <!-- Cambair Tema -->

          </div>
        </div>
      </nav>
      
</div>
