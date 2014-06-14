<div id="background-image"></div>

<div class="container content-area">
    <div class="well text-center">
        <h1>Jelentkezz be&nbsp;<small>az alkalmazás futtatásához</small></h1>
        <form method="post" action="" class="form-inline" role="form">
            <div class="form-group">
                <label class="sr-only" for="username">Felhasználónév</label>
                <input type="text" name="auth[username]" class="form-control" id="username" autofocus placeholder="felhasználónév" />
            </div>
            <div class="form-group">
                <label class="sr-only" for="password">Jelszó</label>
                <input type="password" name="auth[password]" class="form-control" id="password" placeholder="jelszó" />
            </div>
            <button type="submit" class="btn btn-primary">Belépés</button>
        </form>
        {if="$AUTH_FAIL"}
            <br />
            <div class="alert alert-danger"><strong>Hiba!</strong> Feltehetőleg rossz felhasználónév vagy jelszó (vagy alacsony jogosultsági szint).</div>
        {/if}
        {if="$ADMIN"}
            <br />
            <div class="alert alert-info">
                <strong>Hello Admin!</strong><br />
                Mivel nincsenek még felhasználók az adatbázisban, ezért a Rapid készített neked egy Adminisztrátor felhasználót a következő felhasználónév/jelszó párral: <strong>{$ADMIN.username}</strong>/<strong>{$ADMIN.password}</strong>. 
                Ez egy egyszeri üzenet, kérlek <strong>ne feledd el ezt a felhasználónevet és jelszót</strong>, különben telepítheted újra a Rapidot. Most már be tudsz jelentkezni ezzel a felhasználónévvel és jelszóval.
            </div>
        {/if}
        {if="$DBTMP"}
            <br />
            <div class="alert alert-warning">
                <strong>Figyelem!</strong> A konfigurációs fájlban nem lettek beállítva az adatbázis hozzáférési információi. A Rapid jelenleg a temporális könyvtárt használja adatbázisként. 
            </div>
        {/if}
        <br />
    </div>
</div>