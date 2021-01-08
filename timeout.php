<?php

	if(isLoginSessionExpired()) {
		header("Location:forbidden.php?aksi=timeout");
	}

?>