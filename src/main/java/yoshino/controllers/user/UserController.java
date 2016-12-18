package yoshino.controllers.user;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

/**
 * Created by Volio on 2016/12/18.
 */
@Controller
@RequestMapping("/account")
public class UserController {

    @GetMapping()
    public String getAccountIndex() {
        return "user/index";
    }
}
