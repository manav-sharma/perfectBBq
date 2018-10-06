<?php
// contains a set of rules based on particular controller function 

$config = array(
	'pages/addpage' => array(
        array(
            'field' => 'txttile',
            'label' => 'Title',
            'rules' => 'required|is_unique[ tbl_cms.cmsTitle]'
        ),
        array(
            'field' => 'cmsContent',
            'label' => 'Content',
            'rules' => 'required'
        )
    ),
    'users/login' => array(
        array(
            'field' => 'txtUserName',
            'label' => 'Username',
            'rules' => 'required'
        ),
        array(
            'field' => 'txtPassword',
            'label' => 'Password',
            'rules' => 'required'
        )
    ),
    'users/forgotPassword' => array(
        array(
            'field' => 'recovery_email',
            'label' => 'Email',
            'rules' => 'required|valid_email'
        )
    ),
    'users/register' => array(
        array(
            'field' => 'username',
            'label' => 'User Name',
            'rules' => 'required|min_length[3]'
        ),
		array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[5]|max_length[20]'
        ),
        array(
            'field' => 'confirmPassword',
            'label' => 'Confirm Password',
            'rules' => 'required|min_length[5]|matches[password]|max_length[20]'
        ),
		array(
            'field' => 'txtFirstName',
            'label' => 'First Name',
            'rules' => 'trim|required|callback_isAlphaSpace|max_length[60]'
        ),
        array(
            'field' => 'txtLastName',
            'label' => 'Last Name',
            'rules' => 'trim|required|callback_isAlphaSpace|max_length[60]'
        ),
        array(
            'field' => 'cmbCountry',
            'label' => 'Country',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email|is_unique[tbl_users.usrEmail]|max_length[255]'
        )
    ),
    'users/editProfile' => array(
        array(
            'field' => 'txtFirstName',
            'label' => 'First Name',
            'rules' => 'trim|required|callback_isAlphaSpace|max_length[60]'
        ),
        array(
            'field' => 'txtLastName',
            'label' => 'Last Name',
            'rules' => 'trim|required|callback_isAlphaSpace|max_length[60]'
        ),
        array(
            'field' => 'cmbCountry',
            'label' => 'Country',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email|max_length[255]'
        )
    ),
    'users/forgottenpassword' => array(
        array(
            'field' => 'txtUserName',
            'label' => 'Username',
            //'rules' => 'trim|required|min_length[5]|max_length[100]|xss_clean'
            'rules' => 'trim|required|min_length[5]|max_length[100]'
        )
    ),
	'users/changePassword' => array(
        array(
            'field' => 'txtOldPassword',
            'label' => 'Old Password',
            'rules' => 'trim|required|min_length[5]|max_length[30]'
        ),
        array(
            'field' => 'txtNewPassword',
            'label' => 'New Password',
            'rules' => 'trim|required|min_length[5]|max_length[30]'
        ),
        array(
            'field' => 'txtConfirmNewPassword',
            'label' => 'Confirm Password',
            'rules' => 'trim|required|min_length[5]|max_length[30]'
        )
    ),
    'users/addUser' => array(
       array(
            'field' => 'username',
            'label' => 'User Name',
            'rules' => 'required|min_length[3]'
        ),
		array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[5]|max_length[20]'
        ),
        array(
            'field' => 'confirmPassword',
            'label' => 'Confirm Password',
            'rules' => 'required|min_length[5]|matches[password]|max_length[20]'
        ),
		array(
            'field' => 'txtFirstName',
            'label' => 'First Name',
            'rules' => 'trim|required|max_length[60]'
        ),
        array(
            'field' => 'txtLastName',
            'label' => 'Last Name',
            'rules' => 'trim|required|max_length[60]'
        ),
        array(
            'field' => 'cmbCountry',
            'label' => 'Country',
            'rules' => 'trim|required'
        ), 
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email|is_unique[tbl_users.usrEmail]|max_length[255]'
        ) 
    ),
    
    
    'users/addHotel' => array(
       array(
            'field' => 'username',
            'label' => 'User Name',
            'rules' => 'required|min_length[3]'
        ),
        array(
            'field' => 'commision',
            'label' => 'Commission',
            'rules' => 'required'
        ),
        array(
            'field' => 'roomtype[]',
            'label' => 'Room Type',
            'rules' => 'required'
		),
        array(
			'field' => 'hoteltype',
			'label' => 'Hotel Type',
			'rules' => 'required'
		),
		array(
			'field' => 'cmbCity',
			'label' => 'City',
			'rules' => 'required'
		),
        array(
            'field' => 'cmbCity',
            'label' => 'City',
            'rules' => 'required'
        ),
        array(
            'field' => 'usrContact',
            'label' => 'Hotel Contact',
            'rules' => 'required'
        ),
		/*array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|min_length[5]|max_length[20]'
        ),
        array(
            'field' => 'confirmPassword',
            'label' => 'Confirm Password',
            'rules' => 'required|min_length[5]|matches[password]|max_length[20]'
        ),*/
		array(
            'field' => 'hotelName',
            'label' => 'Hotel Name',
            'rules' => 'trim|required|max_length[60]'
        ),
        
        array(
            'field' => 'cmbCountry',
            'label' => 'Country',
            'rules' => 'trim|required'
        ), 
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email|is_unique[tbl_users.usrEmail]|max_length[255]'
        ) 
    ),
    
    
    'contactus/index' => array(
        array(
            'field' => 'contactName',
            'label' => 'Name',
            'rules' => 'required|callback_isAlphaSpace'
        ),
        array(
            'field' => 'contactEmail',
            'label' => 'Email',
            'rules' => 'required|valid_email'
        ),
        array(
            'field' => 'contactSubject',
            'label' => 'Subject',
            'rules' => 'required'
        ),
        array(
            'field' => 'captchaCode',
            'label' => 'Image code',
            'rules' => 'required'
        )
    ),
	'accountissue/index' => array(
        array(
            'field' => 'accountissueName',
            'label' => 'Name',
            'rules' => 'required|callback_isAlphaSpace'
        ),
        array(
            'field' => 'accountissueSubject',
            'label' => 'Subject',
            'rules' => 'required'
        ),		
        array(
            'field' => 'accountissueIssue',
            'label' => 'Issue',
            'rules' => 'required'
        ),
        array(
            'field' => 'captchaCode',
            'label' => 'Image code',
            'rules' => 'required'
        )
    ),
	'advertise/index' => array(
        array(
            'field' => 'advertiseName',
            'label' => 'Name',
            'rules' => 'required|callback_isAlphaSpace'
        ),
        array(
            'field' => 'advertiseOrganization',
            'label' => 'Organization Name',
            'rules' => 'required'
        )
    ),
    'banners/addBanner' => array(
        array(
            'field' => 'bannername',
            'label' => 'Title',
            'rules' => 'required'
        )
    ),
    'clubs/addClub' => array(
		array(
        	'field' => 'clubName',
            'label' => 'Club Name',
            'rules' => 'required|max_length[100]|callback_checkClubCity'
        ),
		array(
        	'field' => 'clubCountry',
            'label' => 'Club Country',
            'rules' => 'required'
        ),
		array(
        	'field' => 'clubLocation',
            'label' => 'Club Location',
			'rules' => 'required'
        ),
		array(
        	'field' => 'clubCity',
            'label' => 'Club City',
            'rules' => 'required'
        ),
		array(
        	'field' => 'address',
            'label' => 'Address',
            'rules' => 'required'
        ),
		array(
        	'field' => 'dress',
            'label' => 'Dress Code',
            'rules' => 'required'
        ),
		array(
        	'field' => 'clubtype',
            'label' => 'Club Type',
            'rules' => 'required'
        )	
	),
	'clubs/postClub' => array(
		array(
        	'field' => 'clubname',
            'label' => 'Club Name',
            'rules' => 'required|max_length[100]|callback_checkClubCity'
        ),
		array(
        	'field' => 'clubCountry',
            'label' => 'Club Country',
            'rules' => 'required'
        ),
		array(
        	'field' => 'location',
            'label' => 'Club Location',
			'rules' => 'required'
        ),
		array(
        	'field' => 'clubCity',
            'label' => 'Club City',
            'rules' => 'required'
        ),
		array(
        	'field' => 'address',
            'label' => 'Address',
            'rules' => 'required'
        ),
		array(
        	'field' => 'dress',
            'label' => 'Dress Code',
            'rules' => 'required'
        ),
		array(
        	'field' => 'clubtype',
            'label' => 'Club Type',
            'rules' => 'required'
        ), 
		array(
        	'field' => 'logo',
            'label' => 'Club Logo',
            'rules' => 'callback_checkMedia[logo]'
		)
	),
	'clubs/addClubPhotos' => array(
		array(
        	'field' => 'image',
            'label' => 'Club Photo',
            'rules' => 'callback_checkPostMedia[image]'
		)
	),
	'uploadpicsandlogos/index' => array(
        array(
            'field' => 'name',
            'label' => 'Name',
            'rules' => 'required'
        )
    )
);
/**/
?>
