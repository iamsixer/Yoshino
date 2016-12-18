package yoshino.controllers.admin;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

/**
 * Created by Volio on 2016/12/18.
 */
@Controller
@RequestMapping(value = "/admin")
public class AdminController {

    @GetMapping()
    public String Home() {
        return "admin/index";
    }
}

