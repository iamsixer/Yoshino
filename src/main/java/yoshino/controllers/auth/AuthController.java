package yoshino.controllers.auth;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;

/**
 * Created by Volio on 2016/12/18.
 */
@Controller
public class AuthController {

    @RequestMapping(value = "/login")
    public String getLogin() {
        return "auth/login";
    }
}
