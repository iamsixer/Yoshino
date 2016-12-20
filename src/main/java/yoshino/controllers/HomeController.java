package yoshino.controllers;

import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

import java.security.Principal;
import java.util.Date;

/**
 * Created by Volio on 2016/12/15.
 */
@Controller
@RequestMapping(value = "/")
public class HomeController {

    @GetMapping
    public String Home(Model model) {
        model.addAttribute("name", "world");
        model.addAttribute("time", new Date());
        return "home";
    }
}
