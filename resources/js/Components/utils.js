export default {
    redirect: function(to) {
        switch(to){
            case 'phoneBook':
                window.location.href = '/phone-book';
                break;
            case 'editPhone':
                window.location.href = '/edit-phone';
                break;
        }
    }
}
