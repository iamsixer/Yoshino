package yoshino.controllers;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;

import java.util.Date;
import java.util.Map;

/**
 * Created by Volio on 2016/12/15.
 */
@Controller
@RequestMapping(value = "/")
public class HomeController {

    @GetMapping("/")
    public String Home(Map<String, Object> model) {
        model.put("name", "world");
        model.put("time", new Date());
        return "home";
    }
}
