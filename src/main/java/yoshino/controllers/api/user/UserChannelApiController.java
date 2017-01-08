package yoshino.controllers.api.user;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;
import yoshino.models.Channel;
import yoshino.services.ChannelService;

import java.security.Principal;

/**
 * Created by Volio on 2017/1/7.
 */
@Controller
@RequestMapping("/api/user/channel")
public class UserChannelApiController {

    private final ChannelService channelService;

    @Autowired
    public UserChannelApiController(ChannelService channelService) {
        this.channelService = channelService;
    }

    @GetMapping("/new")
    @ResponseBody
    public Channel createNewChannel(Principal principal) {
        return channelService.createNewChannel(principal.getName());
    }
}
