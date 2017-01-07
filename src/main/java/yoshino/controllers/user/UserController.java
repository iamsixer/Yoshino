package yoshino.controllers.user;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;
import yoshino.services.ChannelService;

import java.security.Principal;

/**
 * Created by Volio on 2016/12/18.
 */
@Controller
@RequestMapping("/account")
public class UserController {

    private final ChannelService channelService;

    @Autowired
    public UserController(ChannelService channelService) {
        this.channelService = channelService;
    }

    @GetMapping
    public String getAccountIndex(Model model, Principal principal) {
        String publishUrl = channelService.getPublishUrl(principal.getName());
        if (publishUrl != null) {
            model.addAttribute("publishUrl", publishUrl);
        }
        return "user/index";
    }

    @ResponseBody
    @GetMapping("/info")
    public Principal getUserInfo(Principal principal) {
        return principal;
    }
}
