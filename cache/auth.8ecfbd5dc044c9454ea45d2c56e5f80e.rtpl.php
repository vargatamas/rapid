<?php if(!class_exists('raintpl')){exit;}?><div class="well">
    <div class="container">
        <h1>Please sign in</h1>
        <form method="post" action="" class="form-inline" role="form">
            <div class="form-group">
                <label class="sr-only" for="username">Username</label>
                <input type="text" name="auth[username]" class="form-control" id="username" placeholder="john.appleseed" />
            </div>
            <div class="form-group">
                <label class="sr-only" for="password">Password</label>
                <input type="password" name="auth[password]" class="form-control" id="password" placeholder="password" />
            </div>
            <button type="submit" class="btn btn-primary">Sign in</button>
        </form>
        <?php if( $AUTH_FAIL ){ ?>
            <br />
            <div class="alert alert-danger"><strong>Error!</strong> Probably wrong username or password (or low privilege level).</div>
        <?php } ?>
        <?php if( $ADMIN ){ ?>
            <br />
            <div class="alert alert-info">
                <strong>Hello Admin!</strong><br />
                In case that no users in the database, Rapid made an administrator user with this username/password: <strong><?php echo $ADMIN["username"];?></strong>/<strong><?php echo $ADMIN["password"];?></strong>. 
                This is a one-time only information, please do <strong>not forget this username and password</strong> or you have to reinstall Rapid. Now you can Sign in with this username/password.
            </div>
        <?php } ?>
        <?php if( $DBTMP ){ ?>
            <br />
            <div class="alert alert-warning">
                <strong>Warning!</strong> The information of the database were not set in the configuration file. Rapid uses the temporary directory for database. 
            </div>
        <?php } ?>
    </div>
</div>