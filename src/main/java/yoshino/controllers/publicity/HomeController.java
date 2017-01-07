package yoshino.controllers.publicity;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;
import yoshino.services.ChannelService;

import java.util.Date;
import java.util.Map;

/**
 * Created by Volio on 2016/12/15.
 */
@Controller
@RequestMapping(value = "/")
public class HomeController {

    private final ChannelService channelService;

    @Autowired
    public HomeController(ChannelService channelService) {
        this.channelService = channelService;
    }

    @GetMapping
    public String Home(Model model) {
        model.addAttribute("name", "world");
        model.addAttribute("time", new Date());
        return "home";
    }

    @GetMapping("/{id}")
    @ResponseBody
    public Map<String, String> getChannel(@PathVariable("id") Long id) {
        return channelService.getPlayUrl(id);
    }
}
